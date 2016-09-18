<?php

/**
 * ChaCha20加密 PHP版本
 * @author Tyan Boot <tyanboot@outlook.com>
 */



/** chacha20常量
* 就是 expand 32-byte k 的小端序
*/
$ChaChaConst = array(0x61707865, 0x3320646e, 0x79622d32, 0x6b206574 );

/**
 * 四字节char[]转换为int 大端序
 * @param  a char数组
 * @return   int数字
 */
function LE2int(&$a)
{
	return ($a[0] & 0xff) | (($a[1] & 0xff) << 8) | (($a[2] & 0xff) << 16) | (($a[3] & 0xff) << 24);
}

/**
 * int转换为四字节char[] 大端序
 * @param  a int数字
 * @return   char数组
 */
function int2LE(&$a)
{
	$b = array();
	$b = array_pad($b, 4, 0);

	$b[0] |= $a & 0xff;
	$b[1] |= ($a>>8) & 0xff;
	$b[2] |= ($a>>16) & 0xff;
	$b[3] |= ($a>>24) & 0xff;

	return $b;
}

/**
 * 基础操作
 * @param  a
 * @param  b
 * @param  c
 * @param  d
 */
function Qround(&$a, &$b, &$c, &$d)
{
	$a += $b; $d ^= $a; $d = ($d << 16) | ($d >> 16) & 0xffff;
	$c += $d; $b ^= $c; $b = ($b << 12) | ($b >> 20) & 0xfff;
	$a += $b; $d ^= $a; $d = ($d << 8)  | ($d >> 24) & 0xff;
	$c += $d; $b ^= $c; $b = ($b << 7)  | ($b >> 25) & 0x7f;
}

/**
 * 对state的操作
 * @param  state
 * @param  x
 * @param  y
 * @param  z
 * @param  w
 */
function QUARTERROUND(&$state, $x, $y, $z, $w)
{
	//print_r($state);
	Qround($state[$x], $state[$y], $state[$z], $state[$w]);
}


/**
 * 执行20次Qround
 * @param  a
 */
function ChaChaKey(&$a)
{
	for ($i = 0; $i < 10; $i++)
	{
		QUARTERROUND ( $a, 0, 4, 8, 12);
		QUARTERROUND ( $a, 1, 5, 9, 13);
		QUARTERROUND ( $a, 2, 6, 10, 14);
		QUARTERROUND ( $a, 3, 7, 11, 15);
		QUARTERROUND ( $a, 0, 5, 10, 15);
		QUARTERROUND ( $a, 1, 6, 11, 12);
		QUARTERROUND ( $a, 2, 7, 8, 13);
		QUARTERROUND ( $a, 3, 4, 9, 14);
	}
	
}


/**
 * 生成处理后的Key stream用于加密
 * @param  key     密钥
 * @param  counter 计数器
 * @param  nonce   随机数
 * @param  cstate  返回的state
 */
function ChaChaBlock( &$key, $counter, &$nonce, &$cstate)
{
	global $ChaChaConst;

	$state = array();
	$state = array_pad($state, 16, 0);

	$workingState = array();
	$workingState = array_pad($workingState, 16, 0);

	$tmp = array();
	$tmp = array_pad($tmp, 4, 0);

	//初始化常量
	for ($i=0;$i<4;$i++)
	{
		$state[$i] = $ChaChaConst[$i];
	}

	//初始化key
	for ($i=4;$i<12;$i++)
	{
		//每四字节转换成一个int
		//key一共32字节
		//临时变量
		$tmpKey = array();
		$tmpKey = array_pad($tmpKey, 4, 0);


		$tmpKey[0] = $key[($i-4)*4];
		$tmpKey[1] = $key[($i-4)*4+1];
		$tmpKey[2] = $key[($i-4)*4+2];
		$tmpKey[3] = $key[($i-4)*4+3];

		//转换成int
		$state[$i] = LE2int($tmpKey);
	}

	//counter部分
	$state[12] = $counter;

	//nounce部分
	$state[13] = $nonce[0];
	$state[14] = $nonce[1];
	$state[15] = $nonce[2];

	//复制一下
	for ($i=0;$i<16;$i++)
	{
		$workingState[$i] = $state[$i];
	}


	$tmpp = array();
	$tmpp = array_pad($tmpp, 64, 0);

	//变成key stream吧!
	ChaChaKey($state);

	//和原来加一下
	for ($i=0;$i<16;$i++)
	{
		$state[$i] += $workingState[$i];
	}

	//转换成字符数组
	for ($j=0;$j<16;$j++)
	{
		$tmp = array();
		$tmp = array_pad($tmp, 4, 0);

		$tmp = int2LE($state[$j], $tmp);
		$cstate[$j*4] = $tmp[0];
		$cstate[$j*4+1] = $tmp[1];
		$cstate[$j*4+2] = $tmp[2];
		$cstate[$j*4+3] = $tmp[3];
	}

	//应该是这部分完成了
}


/**
 * 加密函数
 * @param  key    
 * @param  counter
 * @param  nonce  
 * @param  Text    待加密的数据,加密之后直接修改本部分数据
 * @param  num     长度
 */
function ChaChaEncrypt(&$key, $counter, &$nonce, &$Text, $num)
{
	//长度除以64取整
	$FloorNum = floor($num/64);
	
	

	for ($i=0;$i<$FloorNum;$i++)
	{
		//生成key block

		$cState = array();
		$cState = array_pad($cState, 64,0);

		ChaChaBlock($key, $counter+$i, $nonce, $cState);

		for ($j=0;$j<64;$j++)
		{
			$Text[$i*64+$j] ^= $cState[$j];
		}
	}

	if ($num%64 != 0)
	{
		//生成key block
		
		$cState = array();
		$cState = array_pad($cState, 64, 0);

		ChaChaBlock($key, $counter+$FloorNum, $nonce, $cState);

		for ($j=0;$j<($num-$FloorNum*64);$j++)
		{
			$Text[$FloorNum*64+$j] ^= $cState[$j];
		}
	}

}


/** 以下内容属于测试内容 */

/** 这个是密钥 */
$key = array(0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f, 0x10, 0x11, 0x12, 0x13, 0x14, 0x15, 0x16, 0x17, 0x18, 0x19, 0x1a, 0x1b, 0x1c, 0x1d, 0x1e, 0x1f );

/** 这是随机数,可自行生成三个数字,需要注意的是,加密解密的时候需要使用相同的随机数! */
$nonce = array(0x00000000, 0x4a000000, 0x00000000);

/** 计数器,用于统计Block数量 */
$counter = 1;

/** 待加密的文本,以16进制数组形式输入 */
$text = array( 0x4c, 0x61, 0x64, 0x69, 0x65, 0x73, 0x20, 0x61, 0x6e, 0x64, 0x20, 0x47, 0x65, 0x6e, 0x74, 0x6c,0x65, 0x6d, 0x65, 0x6e, 0x20, 0x6f, 0x66, 0x20, 0x74, 0x68, 0x65, 0x20, 0x63, 0x6c, 0x61, 0x73,0x73, 0x20, 0x6f, 0x66, 0x20, 0x27, 0x39, 0x39, 0x3a, 0x20, 0x49, 0x66, 0x20, 0x49, 0x20, 0x63,0x6f, 0x75, 0x6c, 0x64, 0x20, 0x6f, 0x66, 0x66, 0x65, 0x72, 0x20, 0x79, 0x6f, 0x75, 0x20, 0x6f,0x6e, 0x6c, 0x79, 0x20, 0x6f, 0x6e, 0x65, 0x20, 0x74, 0x69, 0x70, 0x20, 0x66, 0x6f, 0x72, 0x20,0x74, 0x68, 0x65, 0x20, 0x66, 0x75, 0x74, 0x75, 0x72, 0x65, 0x2c, 0x20, 0x73, 0x75, 0x6e, 0x73,0x63, 0x72, 0x65, 0x65, 0x6e, 0x20, 0x77, 0x6f, 0x75, 0x6c, 0x64, 0x20, 0x62, 0x65, 0x20, 0x69,0x74, 0x2e);

/** 执行加密操作 */
ChaChaEncrypt($key, $counter, $nonce, $text, 114);

/** 打印出加密后的结果 */
print_r($text);

/** 完毕! */