<?PHP
// NOT FOR SHOWING DIRECTLY - THIS IS A LIBRARY - THINGS WILL NOT LOOK
// RIGHT IF SOMEONE ACCESSES THIS FILE DIRECTLY
 
 
// This is the tree class for the root. Internal nodes inherit from this
// type to disply different messages.
class GradeDecisionTree
{
    public $students = array();
    public $cutoff = -1;
    public $right = null;
    public $wrong = null;
 
    function __construct($students, $cutoff)
    {
        $this->students = $students;
        $this->cutoff = $cutoff;
    }
 
    // Generate and return the message that this tree node should display
    public function message()
    {
        $total = count($this->students);
        $good = 0;
        $bad = 0;
        foreach ($this->students as $student) {
            if (array_sum($student) >= $this->cutoff) {
                $good++;
            } else {
                $bad++;
            }
        }
 
        $goodp = (int) (100*$good / $total);
        $badp = (int) (100*$bad / $total);
        return <<<EOT
$total students total
$good students passed ($goodp%)
$bad students failed ($badp%)
EOT;
    }
 
    public function printout($indent)
    {
        $lead = "";
        for ($i = 0; $i < $indent; $i++) $lead = $lead . " ";
 
        print(preg_replace("/\n/", "\n" . $lead, $lead . $this->message()));
        print("\n");
 
        if ($this->right != null) $this->right->printout($indent+2);
        if ($this->wrong != null) $this->wrong->printout($indent+2);
    }
}
 
class GradeDecisionTreeInternal extends GradeDecisionTree
{
    public $question;
    public $gotitright;
 
    function __construct($students, $cutoff, $question, $gotitright)
    {
       parent::__construct($students, $cutoff);
       $this->question = $question;
       $this->gotitright = $gotitright;
    }
 
    // Generate and return the message that this tree node should display
    public function message()
    {
        $total = count($this->students);
        $good = 0;
        $bad = 0;
        foreach ($this->students as $student) {
            if (array_sum($student) >= $this->cutoff) {
                $good++;
            } else {
                $bad++;
            }
        }
 
        $goodp = (int) (100*$good / $total);
        $badp = (int) (100*$bad / $total);
        $rw = $this->gotitright ? "right" : "wrong";
        return <<<EOT
They got $this->question $rw
 
$total students total
$good students passed ($goodp%)
$bad students failed ($badp%)
EOT;
    }
}
 
function entropy($results, $bar)
{
    $good = 0.0;
    $bad = 0.0;
    foreach ($results as $student) {
        if (array_sum($student) >= $bar) {
            $good+=1;
        } else {
            $bad+=1;
        }
    }
 
    if ($good > 0) $good = $good / count($results);
    if ($bad > 0) $bad = $bad / count($results);
 
    if ($good > 0 && $bad > 0)
        return -1 * ($good * log($good, 2.0) + $bad * log($bad, 2.0));
    else
        return 0.0;
}
 
// Recursively break up the tree until we no longer can
function breakTree($tree, $qcutoff, $ncutoff)
{
    $bestq = -1;
    $bestinfogain = $ncutoff;
    $currententropy = entropy($tree->students, $qcutoff);
 
    $numss = (float) count($tree->students);
    $numqs = count($tree->students[0]);
 
    for ($q = 0; $q < $numqs; $q++) {
        $right = array();
        $wrong = array();
 
        foreach ($tree->students as $student) {
            if ($student[$q] == 1) {
                $right[] = $student;
            } else {
                $wrong[] = $student;
            }
        }
 
        $rentropy = entropy($right, $qcutoff);
        $wentropy = entropy($wrong, $qcutoff);
        $newe = $rentropy * count($right) / $numss
                + $wentropy * count($wrong) / $numss;
 
        $gain = $currententropy - $newe;
        if ($q == 12) {
            print("$q $gain $newe $currententropy $numss $rentropy $wentropy \n");
        }
 
        if ($gain >= $bestinfogain) {
            $bestinfogain = $gain;
            $bestq = $q;
        }
    }
 
    if ($bestq == -1) return; // Don't split if we don't need to
 
    $right = array();
    $wrong = array();
 
    foreach ($tree->students as $student) {
        if ($student[$bestq] == 1) {
            $right[] = $student;
        } else {
            $wrong[] = $student;
        }
    }
 
    if (count($right) > 0 && count($wrong) > 0) {
        $tree->right = new GradeDecisionTreeInternal($right, $qcutoff, $bestq, true);
        $tree->wrong = new GradeDecisionTreeInternal($wrong, $qcutoff, $bestq, false);
        breakTree($tree->right, $qcutoff, $ncutoff);
        breakTree($tree->wrong, $qcutoff, $ncutoff);
    }
}
 
// THIS IS THE FUCTION YOU SHOULD USE. YOU CAN IGNORE THE REST
function mineGrades($numberCorrect, $noisethreshold, $key, $answers)
{
    $graded = array();
    $numq = count($key);
    foreach ($answers as $test) {
        $response = array();
        for ($i = 0; $i < $numq; $i++) {
            $response[] = ( ($test[$i] == $key[$i]) ? 1 : 0 );
        }
        $graded[] = $response;
    }
 
    //graded now contains a whole bunch of binary arrays
    $tree = new GradeDecisionTree($graded, $numberCorrect);
    breakTree($tree, $numberCorrect, $noisethreshold);
    return $tree;
}
 
// All of this commented-out code is test code.
/*
$t = mineGrades(2, .5,
array('a', 'b', 'c'),
array( array('a', 'b', 'c'),
array('a', 'd', 'd'),
array('a', 'd', 'd'),
array('a', 'd', 'd'),
array('a', 'b', 'd'),
array('d', 'b', 'c'),
array('d', 'b', 'c'),
array('d', 'b', 'c')));
print($t->message() . "\n");
 
$file = fopen('samplein', "r");
$key = str_split(trim(fgets($file)));
$sdata = array();
while (!feof($file)) {
$sdata[] = str_split(trim(fgets($file)));
}
 
$t = mineGrades(10, .001, $key, $sdata);
print($t->message() . "\n");
$t->printout(0);
*/
 
?>
