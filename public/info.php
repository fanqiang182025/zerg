<?php
//phpinfo();
//$a = range(0, 1000);
//refcount:1 is_ref:0     refcount有几个变量指向空间
//var_dump(memory_get_usage());
//php cow机制  copy on write
//只有修改操作才会copy----没有新开辟空间
//$b = &$a;
//refcount:2 is_ref:0
//var_dump(memory_get_usage());

//对a进行写操作
//$a = range(0, 1000);
//var_dump(memory_get_usage());


//php变量通过Zend引擎处理
//zval 结构体
// $a = range(0, 3);
// xdebug_debug_zval('a');
// $data = ['a','b','c'];
// foreach ($data as $key => $value) {
// 	var_dump($data);
// 	$value = &$data[$key];
// 	var_dump($data);
// }

// // 0 a data[0] = &a  &abc
// // 1 b data[0] = b data[1] = &b b&bc
// // 2 c data[0] = b

// $k = 0;
// var_dump(memory_get_peak_usage());
// $k = 1;
// var_dump(memory_get_peak_usage());
// $people = array("Bill", "Steve", "Mark", "David");

// reset($people); 

// while (list($key, $val) = each($people))
// {
// 	echo "$key => $val<br>";
// }

// $a=array("red","green","blue","yellow","brown");
// $random_keys=array_rand($a,3);
// var_dump($random_keys);exit;

//$dir = './static';

// function loopDir($dir)
// {
// 	$handle = opendir($dir);
// 	var_dump(readdir($handle));exit;
// 	while (($file = readdir($handle)) !== false) {
		
// 		if ($file != '.' && $file!= '..') {
// 			echo $file."\n";
// 			if (filetype($dir.'/'.$file) == 'dir') {
// 				loopDir($dir.'/'.$file);
// 			}
// 		}
// 	}
// }
// loopDir($dir);
// setcookie('name',123);
// var_dump($_COOKIE);

// session_start();
// $_SESSION["admin"] = true;

// var_dump($_SESSION);



// for ($i=0; $i < $count-1; $i++) { 
// 	for ($j=0; $j < $count-1-$i; $j++) { 
// 		if ($array[$j] > $array[$j+1]) {
// 			$n = $array[$j];
// 			$array[$j] = $array[$j+1];
// 			$array[$j+1] = $n;
// 		}
// 	}
	
// }

// for ($i=1; $i < $count; $i++) { 
// 	$temp = $array[$i];

// 	$j = $i-1;
// 	while($j>=0 && $temp<$array[$j]) {
// 		$array[$j+1] = $array[$j];
// 		$j--;
// 	}
// 	$array[$j+1] = $temp;
// }

// $h = 1;
// while ($h<=$count/3) {
// 	$h = $h*3+1;
// }

// for ($gap=$h; $gap>=1; $gap =($gap-1)/3) { 
// 	for ($i=$gap; $i < $count; $i++) { 
// 		$temp = $array[$i];

// 		$j = $i-$gap;
// 		while($j>=0 && $temp<$array[$j]) {
// 			$array[$j+$gap] = $array[$j];
// 			$j-=$gap;
// 		}
// 		$array[$j+$gap] = $temp;
// 	}
// }


// for ($i=0; $i < $count-1; $i++) { 
// 	$minIndex = $i;
// 	for ($j=$i+1; $j < $count; $j++) { 
// 		if ($array[$minIndex] > $array[$j] ) {
// 			$minIndex = $j;
// 		}
// 	}
// 	if ($minIndex != $i) {
// 		$temp = $array[$i];
// 		$array[$i] = $array[$minIndex];
// 		$array[$minIndex] = $temp;
// 	}	
// }

//array_merge
// $arr1 = [1,2,3];
// $arr2 = [4,5,6];
// $arr3 = [7,8,6];
// function merge(){
// 	$array = [];
// 	$arrays = func_get_args();
// 	foreach ($arrays as $key => $value) {
// 		if (is_array($arrays)) {
// 			foreach ($value as $k => $v) {
// 				$array[] = $v;
// 			}
// 		}
		
// 	}
// 	return $array;
// }
//var_dump(merge ($arr1,$arr2,$arr3));


    // echo '<table border="1">';
    // $x = 0;
    // while ($x < 10) {
    //     echo '<tr align="center">';
    //     $y = 0;
    //         while ($y < 10) {
    //             echo '<td>'.($x*10+$y).'</td>';
    //             $y++;
    //         }
    //     echo '</tr>';
    //     $x++;
    // }
    // echo '</table>';



// abstract class A{
	

// 	protected function eat(){
// 		echo 456;
// 	}

// 	abstract public function go();
// }

// interface jiekou{
// 	public function say($value);
// 	public function go();
// }

// interface jiekou2{
// 	public function eat2($value);
// }





// class C implements jiekou{
// 	public function say($value){
// 		echo "我是jiekou";
// 	}
// 	public function go(){
// 		echo "我是jiekou---go";
// 	}
// }

// class B implements jiekou,jiekou2{

// 	public function say($value){
// 		echo "say";
// 	}

// 	public function go($a=0){
// 		echo $a;
// 	}

// 	public function eat2($value){
// 		echo 'eat';
// 	}
// }

// $b = new B();
// $b->eat2(1);

// class User{
// 	public $id;
// 	public $name;
// 	public $mobile;

// 	public $Db;
// 	function __construct($id){
// 		$this->Db = new Mysqli('127.0.0.1','root','root','test');
// 		$res = $this->Db->query('select * from user where id='.$id);

// 		$data = $res->etch_assoc($res);
// 		$this->id = $data['id'];
// 		$this->name = $data['name'];
// 		$this->mobile = $data['mobile'];
// 	}


// 	function __destruct(){
// 		$this->Db->query("update user set name='{$this->name}' ,
// 		 mobile='{$this->mobile}' where id = {$this->id}");
// 	}
// }

// $user = new User(1);

// $user->name = 'jay';
// $user->mobile = 15234152623;

// abstract class EventGenerator{
// 	private $observer = [];
// 	public function addObserver(Observer $observer){
// 		$this->observer[] = $observer;
// 	}
// 	public function notify(){
// 		foreach ($this->observer as $value) {
// 			$value->update();
// 		}
// 	}
// }


// interface Observer{
// 	public function update();
// }

// class Event extends EventGenerator{
// 	public function trigger(){
// 		echo "我下单了</br>";
// 		$this->notify();
// 	}
// }

// class ModifyStock implements Observer{
// 	public function update(){
// 		echo '成功修改库存</br>';
// 	}
// }

// class SendMessage implements Observer{
// 	public function update(){
// 		echo '成功发送短信</br>';
// 	}
// }

// class SendEmail implements Observer{
// 	public function update(){
// 		echo '成功发送邮件</br>';
// 	}
// }



// $modifyStock = new ModifyStock();
// $sendMessage = new SendMessage();
// $sendEmail = new SendEmail();

// $event = new Event();
// $event->addObserver($modifyStock);
// $event->addObserver($sendMessage);
// $event->addObserver($sendEmail);
// $event->trigger();

// class EchoText{
// 	protected $decorator = [];

// 	public function index(){
// 		$this->beforeEcho();
// 		echo "我是装饰器";
// 		$this->afterEcho();
// 	}

// 	public function addDecorator(Decorator $decorator){
// 		$this->decorator[] = $decorator;
// 	}

// 	public function beforeEcho(){
// 		foreach ($this->decorator as $value) {
// 			$value->before();
// 		}

// 	}

// 	public function afterEcho(){
// 		$tmp = array_reverse($this->decorator);
// 		foreach ($tmp as  $value) {
// 			$value->after();
// 		}
// 	}
// }

// interface Decorator{
// 	public function before();
// 	public function after();
// }


// class ColorDecorator implements Decorator
// {
// 	public $color;
// 	function __construct($color){
// 		$this->color = $color;
// 	}
// 	public function before(){
// 		echo "<div style='color:{$this->color}'>";
// 	}

// 	public function after(){
// 		echo "</div>";
// 	}
// }

// class SizeDecorator implements Decorator
// {
// 	public $size;
// 	function __construct($size){
// 		$this->size = $size;
// 	}
// 	public function before(){
// 		echo "<span style='font-size:{$this->size}px'>";
// 	}

// 	public function after(){
// 		echo "</span>";
// 	}
// }



// $colorDecorator = new ColorDecorator('red');
// $sizeDecorator = new SizeDecorator(20);

// $echotext = new EchoText();
// $echotext->addDecorator($colorDecorator);
// $echotext->addDecorator($sizeDecorator); 
// $echotext->index();














	













 










?>