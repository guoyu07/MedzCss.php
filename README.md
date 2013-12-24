MedzCss.php
===========

Medz Css Service Beta V1

MedzCss将css进行php化，目前包含的php语句有变量赋值，流程控制语句和function
虽然大部分php代码未移植编译，但是MedzCss中提供了php原生嵌入
标签如下：
	<!!> : 原始php代码嵌入，同时支持<?php?>格式嵌入
	<var:varName="Value"> : 变量赋值，等同于$varName = #Value
	<if:expr> : if()条件判断语句开始
	</if> : if()条件结束
	<elseif:expr> if()分支else if() 语句
	<else> if()的else语句
	<while:expr> : while()语句
	</while> : 结束while()
	<for:expr;expr;expr> : for()循环语句
	</for> : 结束for()
	<foreach:expr> : foreach() 循环语句开始
	</foreach> foreach()语句结束
	<function> :定于方法
	</function>　：　结束方法
	<echo:value> : 等同于echo()函数
	<print:value> : 等同于print()函数
	<die:value> : 等同于die()函数
	<print_r:value> : 等同于print_r()函数
	
	Tips: 四个输出函数(echo|print|print_r|die)只能用来输出变量或函数等，不可直接输出值，而且也无直接输出value的必要
	虽然编译的都是流程控制语句，但是其中支持原始php代码的嵌入，所以理论上是可以达到php语句的所有功能，不过用于处理动态css代码没有那个必要！
	
	修订日期：2013年12月25日
	项目发起人：Sivay Du
	MedzCss诚邀各位大牛一起完善，作为一个php框架小组件还是很有效果的！