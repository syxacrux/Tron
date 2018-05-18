<?php
// bsf管理模板函数文件
use redis\RedisPackage;
/**
 * 给树状菜单添加level并去掉没有子菜单的菜单项
 * @param  array   $data  [description]
 * @param  integer $root  [description]
 * @param  string  $child [description]
 * @param  string  $level [description]
 */
function memuLevelClear($data, $root=1, $child='child', $level='level')
{
    if (is_array($data)) {
        foreach($data as $key => $val){
        	$data[$key]['selected'] = false;
        	$data[$key]['level'] = $root;
        	if (!empty($val[$child]) && is_array($val[$child])) {
				$data[$key][$child] = memuLevelClear($val[$child],$root+1);
        	}else if ($root<3&&$data[$key]['menu_type']==1) {
        		unset($data[$key]);
        	}
        	if (empty($data[$key][$child])&&($data[$key]['level']==1)&&($data[$key]['menu_type']==1)) {
        		unset($data[$key]);
        	}
        }
        return array_values($data);
    }
    return array();
}

/**
 * [rulesDeal 给树状规则表处理成 module-controller-action ]
 * @AuthorHTL
 * @DateTime  2017-01-16T16:01:46+0800
 * @param     [array]                   $data [树状规则数组]
 * @return    [array]                         [返回数组]
 */
function rulesDeal($data)
{   
    if (is_array($data)) {
        $ret = [];
        foreach ($data as $k1 => $v1) {
            $str1 = $v1['name'];
            if (is_array($v1['child'])) {
                foreach ($v1['child'] as $k2 => $v2) {
                    $str2 = $str1.'-'.$v2['name'];
                    if (is_array($v2['child'])) {
                        foreach ($v2['child'] as $k3 => $v3) {
                            $str3 = $str2.'-'.$v3['name'];
                            $ret[] = $str3;
                        }
                    }else{
                        $ret[] = $str2;
                    }
                }
            }else{
                $ret[] = $str1;
            }
        }
        return $ret;
    }
    return [];
}

/**
 * cookies加密函数
 * @param string 加密后字符串
 */
function encrypt($data, $key = 'kls8in1e') 
{ 
    $prep_code = serialize($data); 
    $block = mcrypt_get_block_size('des', 'ecb'); 
    if (($pad = $block - (strlen($prep_code) % $block)) < $block) { 
        $prep_code .= str_repeat(chr($pad), $pad); 
    } 
    $encrypt = mcrypt_encrypt(MCRYPT_DES, $key, $prep_code, MCRYPT_MODE_ECB); 
    return base64_encode($encrypt); 
} 

/**
 * cookies 解密密函数
 * @param array 解密后数组
 */
function decrypt($str, $key = 'kls8in1e') 
{ 
    $str = base64_decode($str); 
    $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB); 
    $block = mcrypt_get_block_size('des', 'ecb'); 
    $pad = ord($str[($len = strlen($str)) - 1]); 
    if ($pad && $pad < $block && preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)) { 
        $str = substr($str, 0, strlen($str) - $pad); 
    } 
    return unserialize($str); 
}

/**
 * 去除字符串包含的空格
 * @author zjs 2018-01-25
 */
function trimall($str){
    $front = array(" ","　","\t","\n","\r");
    $back = array("","","","","");
    return str_replace($front,$back,$str);
}

/**
 * 对二维数组内的字段进行从大到小的排序
 * $arrays   必需 规定输入的数组。
 * $sort_key 二维数组内键名
 * int $sort_order 可选 规定排序类型 1.SORT_ASC - 默认，按升序排列(A-Z) 2.SORT_DESC - 按降序排列。(Z-A)
 * int $sort_type 以指定排序的类型 可能的值是SORT_REGULAR、SORT_NUMERIC和SORT_STRING
 * @return bool
 * @author zjs 2018/2/28
 */
function my_sort($arrays,$sort_key,$sort_order=SORT_DSC,$sort_type=SORT_NUMERIC ){
    if(is_array($arrays)){
        foreach ($arrays as $array){
            if(is_array($array)){
                $key_arrays[] = $array[$sort_key];
            }else{
                return false;
            }
        }
    }else{
        return false;
    }
    array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
    return $arrays;
}

//获取当前所属主机信息
function osname()
{
	$agent = $_SERVER['HTTP_USER_AGENT'];
	$arr['ip'] = getenv('REMOTE_ADDR');
	if (mb_eregi('Linux', $agent)) {
		$arr['name'] = 'linux';
		$arr['path'] = '/All';
	} else if (mb_eregi('Windows', $agent)) {
		$arr['name'] = 'windows';
		$arr['path'] = 'x:';
	} else if (mb_eregi('Mac os', $agent)) {
		$arr['name'] = 'mac os';
		$arr['path'] = '/Volumes/All';
	}
	return $arr;
}





