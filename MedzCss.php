<?php
/*****************************************************************************************************/
/*************************************Medz Css Service************************************************/
/********************** Service footsteps developers：Sivay Du ***************************************/
/********************** Official Website：	Http://Www.Medz.Cn ***************************************/
/********************** Contact  E-mail:	lovevipdsw@vip.qq.com ************************************/
/********************** Contact  QQ：		910338384/528164511 **************************************/
/********************** Contact  Skype：	lovevipdsw@live.com **************************************/
/************DeBug说明：*************DebBug默认为开启状态*********************************************/
/*****************DeBug开启后Medz Css 服务类将不编译缓存Css*******************************************/
/*****************将DEBUG设置为True即开启DeBug********************************************************/
/*****************将DEBUG设置为False即关闭DeBug*******************************************************/
/*****************************************************************************************************/
/***********/Define( 'DEBUG', true );/**********************设置是否开启调试模式 *********************/
/***********/Define( 'PATH', dirname(__FILE__) );/**********定义网站根目录 ***************************/
/***********/Define( 'CSSDIR', PATH . '/compile/' );/*******定义css编译后的储存目录 ******************/
/***********/Define( 'SUFFIX', '.php' );/*******************定义编译后的css缓存文件后缀***************/
/***********/Define( 'THIS', 'MedzCss.php' );/**************定义本处理文件名，可以根据自己需求改改 ***/
/***********/Header( 'Content-type: text/css' );/***********定义文本输出文本为CSS样式表（不建议修改）*/
/*****************************************************************************************************/
/************************ Medz Css Service Instantiation Start ***************************************/
/************************ 在此实例化Medz Css 脚本处理类，*********************************************/
/************************ 如果需要其他开发整合服务， *************************************************/
/************************ 可以将本段代码注销。 *******************************************************/
/***********************/ $MedzCss = new MedzCss();/*************实例化MedzCssService*****************/
/***********************/ $MedzCss->Export();/*********执行Export()方法输出编译后的CSS样式表**********/
/************************ Medz Css Service Instantiation End *****************************************/
/*****************************************************************************************************/
Class MedzCss {

	/*
	*	定义This-Css服务器CSS路径
	*/
	private $CssUrl;
	
	/*
	* 储存在$tag的标签数组
	*/
	private $tag;
	
	/*
	*	析构方法
	*	在function调用前执行处理预先数据
	*/
	public function __construct() {
		$this->CssUrl = $this->_getCssUrl();
		$this->tag = $this->_getTag();
	}
	
	/*
	*	执行Css处理后的Css样式表输出
	*	echo @css File
	*/
	public function Export() {
		exit( $this->compile() );
	}
	
	/*
	*	文件检验编译
	* 	return @include $path	编译后的css文件
	*/
	private function compile() {
		$path = CSSDIR . md5( $this->CssUrl ) . SUFFIX;
		if( !$this->is_file( $path ) || DEBUG == true ) {
			$this->process( $this->CssUrl, $path );
		}
		return include $path;
	}
	
	/*
	*	编译Css流程控制标签
	*	@param string $path 待编译css文件路径
	*	@param string $path2 储存的编译文件路径
	*/
	private function process( $path, $path2 ) {
		$data = $this->read( $path );
		foreach($this->tag as $v) {
			$data = $this->replace( $v['regular'], $v['result'], $data );
		}
		$this->write( $path2, $data );
	}
	
	/*
	*	Medz Css Tag 替换
	*	@param string $regular 	编译标签正则表达式
	*	@param string $result  	编译后正则替换字符
	*	@param string $data		待编译的字符集
	*	return string 标签替换后的字符集
	*/
	public function replace( $regular, $result, $data ) {
		return preg_replace( $regular, $result, $data );
	}
	
	/**
	 * 写文件
	 *
	 * @param string $fileName 文件绝对路径
	 * @param string $data 数据
	 * @param string $method 读写模式,默认模式为rb+
	 * @param bool $ifLock 是否锁文件，默认为true即加锁
	 * @param bool $ifCheckPath 是否检查文件名中的“..”，默认为true即检查
	 * @param bool $ifChmod 是否将文件属性改为可读写,默认为true
	 * @return int 返回写入的字节数
	 */
	private function write( $fileName, $data, $method = 'rb+', $ifLock = true, $ifCheckPath = true, $ifChmod = true ) {
		touch( $fileName );
		if ( !$handle = fopen( $fileName, $method ) ) return false;
		$ifLock && flock( $handle, LOCK_EX );
		$writeCheck = fwrite( $handle, $data );
		$method == 'rb+' && ftruncate( $handle, strlen( $data ) );
		fclose( $handle );
		$ifChmod && chmod( $fileName, 0777 );
		return $writeCheck;
	}
	
	/**
	 * 读取文件
	 *
	 * @param string $fileName 文件绝对路径
	 * @param string $method 读取模式默认模式为rb
	 * @return string 从文件中读取的数据
	 */
	private function read( $fileName, $method = 'rb' ) {
		$data = '';
		if ( !$handle = fopen( $fileName, $method ) ) return false;
		while ( !feof( $handle ) )
			$data .= fgets( $handle, 4096 );
		fclose( $handle );
		return $data;
	}
	
	/*
	*	判断文件是否存在
	*	@String $path
	*	return booleam
	*/
	private function is_file( $path ) {
		return $path ? is_file( $path ) : false;
	}
	
	/*
	*	获取并处理CssUrl
	*	return @String $url
	*	demo：E:\\www\res/css/style.css
	*/
	private function _getCssUrl() {
		$url1 = $_SERVER['PATH_INFO'];
		$url2 = $_SERVER['REQUEST_URI'];
		$url3 = $_SERVER['PHP_SELF'];
		if(!$url1) {
			if(!$url2) {
				$url = $this->_processCssUrl( $url3 );
			} else {
				$url = $this->_processCssUrl( $url2 );
			}
		} else {
			$url = $url1;
		}
		if( !$url ) {
			exit( 'Sorry, your environment can not get the current url php processing path , configure the perfect php environment , which can not be used MedzCss We are sorry !' );
		}
		return PATH . $url;
	}
	
	/*
	*	处理Url
	*	@Path $url 脚步后面的部分url
	*	return @String $url
	*	demo：E:\\www\MedzCss.php/res/css/style.css
	*/
	private function _processCssUrl( $url ) {
		$data = explode( THIS, $url );
		return $data['1'];
	}
	
	private function _getTag() {
		return array(
			array( 'regular' => '/\<var:(.*?)=(.*?)\>/', 'result' => '<?php $\\1=\\2;?>' ),
			array( 'regular' => '/\<(if|while|function|for|foreach):(.*?)\>/', 'result' => '<?php \\1($\\2){?>' ),
			array( 'regular' => '/\<elseif:(.*?)\>/', 'result' => '<?php }elseif($\\1){?>' ),
			array( 'regular' => '/\<else\>/', 'result' => '<?php }else{?>' ),
			array( 'regular' => '/\<\\/(if|for|foreach|while|function)>/', 'result' => '<?php }?>' ),
			array( 'regular' => '/\<(echo|print|print_r|die):(.*?)\>/', 'result' => '<?php \\1($\\2);?>' ),
			array( 'regular' => '/\<!/', 'result' => '<?php ' ),
			array( 'regular' => '/\!>/', 'result' => '?>' ),
			//	array( 'regular' => , 'result' => ),
		);
	}
}
/* 整个处理类库只是简单的标签替换，并非大牛们所做的那种文本数据替换，所以本类库开发较为简单，期待以后能完善。 */