<?php $a=red;?>
html {
	background: <?php echo($a);?>;
}
<?php 
$arr = array(
	'1' => '#111',
	'2' => '#555',
	'3' => '#fff',
	'4' => '#qqq',
	'5' => '#aaa',
	'6' => '#bbb',
);
?>
<?php $a=1;?>
<?php while($a<=6){?>
h<?php echo($a);?> {
	color:<?php echo($arr[$a]);?>;
}
<?php $a++;?>
<?php }?>