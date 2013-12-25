<?php
/*****************************************************************************************************/
/*************************************Medz Css Service************************************************/
/********************** Service footsteps developers��Sivay Du ***************************************/
/********************** Official Website��	Http://Www.Medz.Cn ***************************************/
/********************** Contact  E-mail:	lovevipdsw@vip.qq.com ************************************/
/********************** Contact  QQ��		910338384/528164511 **************************************/
/********************** Contact  Skype��	lovevipdsw@live.com **************************************/
/************DeBug˵����*************DebBugĬ��Ϊ����״̬*********************************************/
/*****************DeBug������Medz Css �����ཫ�����뻺��Css*******************************************/
/*****************��DEBUG����ΪTrue������DeBug********************************************************/
/*****************��DEBUG����ΪFalse���ر�DeBug*******************************************************/
/*****************************************************************************************************/
/***********/Define( 'DEBUG', true );/**********************�����Ƿ�������ģʽ *********************/
/***********/Define( 'PATH', dirname(__FILE__) );/**********������վ��Ŀ¼ ***************************/
/***********/Define( 'CSSDIR', PATH . '/compile/' );/*******����css�����Ĵ���Ŀ¼ ******************/
/***********/Define( 'SUFFIX', '.php' );/*******************���������css�����ļ���׺***************/
/***********/Define( 'THIS', 'MedzCss.php' );/**************���屾�����ļ��������Ը����Լ�����ĸ� ***/
/***********/Header( 'Content-type: text/css' );/***********�����ı�����ı�ΪCSS��ʽ���������޸ģ�*/
/*****************************************************************************************************/
/************************ Medz Css Service Instantiation Start ***************************************/
/************************ �ڴ�ʵ����Medz Css �ű������࣬*********************************************/
/************************ �����Ҫ�����������Ϸ��� *************************************************/
/************************ ���Խ����δ���ע���� *******************************************************/
/***********************/ $MedzCss = new MedzCss();/*************ʵ����MedzCssService*****************/
/***********************/ $MedzCss->Export();/*********ִ��Export()�������������CSS��ʽ��**********/
/************************ Medz Css Service Instantiation End *****************************************/
/*****************************************************************************************************/
Class MedzCss {

	/*
	*	����This-Css������CSS·��
	*/
	private $CssUrl;
	
	/*
	* ������$tag�ı�ǩ����
	*/
	private $tag;
	
	/*
	*	��������
	*	��function����ǰִ�д���Ԥ������
	*/
	public function __construct() {
		$this->CssUrl = $this->_getCssUrl();
		$this->tag = $this->_getTag();
	}
	
	/*
	*	ִ��Css������Css��ʽ�����
	*	echo @css File
	*/
	public function Export() {
		exit( $this->compile() );
	}
	
	/*
	*	�ļ��������
	* 	return @include $path	������css�ļ�
	*/
	private function compile() {
		$path = CSSDIR . md5( $this->CssUrl ) . SUFFIX;
		if( !$this->is_file( $path ) || DEBUG == true ) {
			$this->process( $this->CssUrl, $path );
		}
		return include $path;
	}
	
	/*
	*	����Css���̿��Ʊ�ǩ
	*	@param string $path ������css�ļ�·��
	*	@param string $path2 ����ı����ļ�·��
	*/
	private function process( $path, $path2 ) {
		$data = $this->read( $path );
		foreach($this->tag as $v) {
			$data = $this->replace( $v['regular'], $v['result'], $data );
		}
		$this->write( $path2, $data );
	}
	
	/*
	*	Medz Css Tag �滻
	*	@param string $regular 	�����ǩ������ʽ
	*	@param string $result  	����������滻�ַ�
	*	@param string $data		��������ַ���
	*	return string ��ǩ�滻����ַ���
	*/
	public function replace( $regular, $result, $data ) {
		return preg_replace( $regular, $result, $data );
	}
	
	/**
	 * д�ļ�
	 *
	 * @param string $fileName �ļ�����·��
	 * @param string $data ����
	 * @param string $method ��дģʽ,Ĭ��ģʽΪrb+
	 * @param bool $ifLock �Ƿ����ļ���Ĭ��Ϊtrue������
	 * @param bool $ifCheckPath �Ƿ����ļ����еġ�..����Ĭ��Ϊtrue�����
	 * @param bool $ifChmod �Ƿ��ļ����Ը�Ϊ�ɶ�д,Ĭ��Ϊtrue
	 * @return int ����д����ֽ���
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
	 * ��ȡ�ļ�
	 *
	 * @param string $fileName �ļ�����·��
	 * @param string $method ��ȡģʽĬ��ģʽΪrb
	 * @return string ���ļ��ж�ȡ������
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
	*	�ж��ļ��Ƿ����
	*	@String $path
	*	return booleam
	*/
	private function is_file( $path ) {
		return $path ? is_file( $path ) : false;
	}
	
	/*
	*	��ȡ������CssUrl
	*	return @String $url
	*	demo��E:\\www\res/css/style.css
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
	*	����Url
	*	@Path $url �Ų�����Ĳ���url
	*	return @String $url
	*	demo��E:\\www\MedzCss.php/res/css/style.css
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
/* �����������ֻ�Ǽ򵥵ı�ǩ�滻�����Ǵ�ţ�������������ı������滻�����Ա���⿪����Ϊ�򵥣��ڴ��Ժ������ơ� */