<?php

/**
 * ChaCha20加密 PHP版本
 * @author Tyan Boot <tyanboot@outlook.com>
 */

namespace Core\Utils;

class ChaCha20
{
    /** chacha20常量
     * 就是 expand 32-byte k 的小端序
     */
    private $ChaChaConst = array( 0x61707865, 0x3320646e, 0x79622d32, 0x6b206574 );


    private $key;
    private $counter;
    private $nonce;

    private $Text;

    public function __construct($key, $counter, $nonce)
    {
        $this->key = $key;
        $this->counter = $counter;
        $this->nonce = $nonce;
    }

    public function ResetCount($counter)
    {
        $this->counter = $counter;
    }

    /**
     * 四字节char[]转换为int 大端序
     * @param  char
     * @return   int
     */
    private function LE2int(&$a)
    {
        return ($a[0] & 0xff) | (($a[1] & 0xff) << 8) | (($a[2] & 0xff) << 16) | (($a[3] & 0xff) << 24);
    }

    /**
     * int转换为四字节char[] 大端序
     * @param  int
     * @return   array
     */
    private function int2LE(&$a)
    {
        $b = array();
        $b = array_pad($b, 4, 0);

        $b[0] |= $a & 0xff;
        $b[1] |= ($a >> 8) & 0xff;
        $b[2] |= ($a >> 16) & 0xff;
        $b[3] |= ($a >> 24) & 0xff;

        return $b;
    }


    /**
     * 基础操作
     * @param  a
     * @param  b
     * @param  c
     * @param  d
     */
    private function Qround(&$a, &$b, &$c, &$d)
    {
        $a += $b;
        $d ^= $a;
        $d = ($d << 16) | ($d >> 16) & 0xffff;
        $c += $d;
        $b ^= $c;
        $b = ($b << 12) | ($b >> 20) & 0xfff;
        $a += $b;
        $d ^= $a;
        $d = ($d << 8) | ($d >> 24) & 0xff;
        $c += $d;
        $b ^= $c;
        $b = ($b << 7) | ($b >> 25) & 0x7f;
    }


    /**
     * 对state的操作
     * @param  state
     * @param  x
     * @param  y
     * @param  z
     * @param  w
     */
    private function QUARTERROUND(&$state, $x, $y, $z, $w)
    {
        //print_r($state);
        $this->Qround($state[$x], $state[$y], $state[$z], $state[$w]);
    }

    /**
     * 执行20次Qround
     * @param  a
     */
    private function ChaChaKey(&$a)
    {
        for ($i = 0; $i < 10; $i++)
        {
            $this->QUARTERROUND($a, 0, 4, 8, 12);
            $this->QUARTERROUND($a, 1, 5, 9, 13);
            $this->QUARTERROUND($a, 2, 6, 10, 14);
            $this->QUARTERROUND($a, 3, 7, 11, 15);
            $this->QUARTERROUND($a, 0, 5, 10, 15);
            $this->QUARTERROUND($a, 1, 6, 11, 12);
            $this->QUARTERROUND($a, 2, 7, 8, 13);
            $this->QUARTERROUND($a, 3, 4, 9, 14);
        }

    }

    /**
     * 生成处理后的Key stream用于加密
     * @param  key //密钥
     * @param  counter //计数器
     * @param  nonce //随机数
     * @param  cstate //返回的state
     */
    private function ChaChaBlock(&$cstate)
    {
        //global $ChaChaConst;

        $state = array();
        $state = array_pad($state, 16, 0);

        $workingState = array();
        $workingState = array_pad($workingState, 16, 0);

        $tmp = array();
        $tmp = array_pad($tmp, 4, 0);

        //初始化常量
        for ($i = 0; $i < 4; $i++)
        {
            $state[$i] = $this->ChaChaConst[$i];
        }

        //初始化key
        for ($i = 4; $i < 12; $i++)
        {
            //每四字节转换成一个int
            //key一共32字节
            //临时变量
            $tmpKey = array();
            $tmpKey = array_pad($tmpKey, 4, 0);


            $tmpKey[0] = $this->key[($i - 4) * 4];
            $tmpKey[1] = $this->key[($i - 4) * 4 + 1];
            $tmpKey[2] = $this->key[($i - 4) * 4 + 2];
            $tmpKey[3] = $this->key[($i - 4) * 4 + 3];

            //转换成int
            $state[$i] = $this->LE2int($tmpKey);
        }

        //counter部分
        $state[12] = $this->counter;

        //nounce部分
        $state[13] = $this->nonce[0];
        $state[14] = $this->nonce[1];
        $state[15] = $this->nonce[2];

        //复制一下
        for ($i = 0; $i < 16; $i++)
        {
            $workingState[$i] = $state[$i];
        }


        $tmpp = array();
        $tmpp = array_pad($tmpp, 64, 0);

        //变成key stream吧!
        $this->ChaChaKey($state);

        //和原来加一下
        for ($i = 0; $i < 16; $i++)
        {
            $state[$i] += $workingState[$i];
        }

        //转换成字符数组
        for ($j = 0; $j < 16; $j++)
        {
            $tmp = array();
            $tmp = array_pad($tmp, 4, 0);

            $tmp = $this->int2LE($state[$j]);
            $cstate[$j * 4] = $tmp[0];
            $cstate[$j * 4 + 1] = $tmp[1];
            $cstate[$j * 4 + 2] = $tmp[2];
            $cstate[$j * 4 + 3] = $tmp[3];
        }

        $this->counter++;

        //应该是这部分完成了
    }


    /**
     * 加密函数
     * @param  Text
     * @param  num
     * @return mixed
     */
    public function ChaChaEncrypt($Text, $num)
    {
        $Text = str_split($Text);

        foreach ($Text as $Key => $char)
        {
            $Text[$Key] = ord($char);
        }
        //长度除以64取整
        $FloorNum = floor($num / 64);

        for ($i = 0; $i < $FloorNum; $i++)
        {
            //生成key block

            $cState = array();
            $cState = array_pad($cState, 64, 0);

            $this->ChaChaBlock($cState);

            for ($j = 0; $j < 64; $j++)
            {
                $Text[$i * 64 + $j] ^= $cState[$j];
            }
        }

        if ($num % 64 != 0)
        {
            //生成key block

            $cState = array();
            $cState = array_pad($cState, 64, 0);

            $this->ChaChaBlock($cState);

            for ($j = 0; $j < ($num - $FloorNum * 64); $j++)
            {
                $Text[$FloorNum * 64 + $j] ^= $cState[$j];
            }
        }

        $this->Text = $Text;
        //print_r($this->Text);
        return $this->ToString();
    }

    public function ToString()
    {
        $str = '';
        // TODO: Implement __toString() method.
        foreach ($this->Text as $char)
        {
            $str = $str . chr($char);

        }
        return $str;
    }
}