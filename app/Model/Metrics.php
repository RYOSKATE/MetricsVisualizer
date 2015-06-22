<?php
class Metrics extends AppModel 
{
    function getMetricsTable($data) 
    {

    	//model名,レイヤー、全ファイル数、血管のあるファイル数、欠陥数
//data[0]=Array
		// (
		//     [Graph] => Array
		//         (
		//             [model] => testA
		//             [file_path] => vendor/qcom/proprietary/telephony-apps/ims/src/com/qualcomm/ims/ImsSenderRxr.java
		//             [3] => 1
		//         )

		// )
		$modelName = $data[0]['Graph']['model'];
    	$newData = array();
    	for ($i = 0; $i < 7; ++$i)
    	{
    		$newData[$i]=array('ModelLayer'=>
    							array(
									'model'           =>$modelName,
									'layer'           =>$i,
									'all_file_num'    =>0,
									'defect_file_num' =>0,
									'defect_per_file' =>0,
									'defect_num'      =>0,
    								 )
    							);
    	}

    	for ($i = 0; $i < count($data); ++$i)
	   	{
	   		$defact = $data[$i]['Graph'][3];
			$filePath = $data[$i]['Graph']['file_path'];
			$layer = $this->getLayer($filePath);
			if($layer < 0 ||6 < $layer)
			{
				continue;
			}
			++$newData[$layer]['ModelLayer']['all_file_num'];
			if(0<$defact)
			{
				++$newData[$layer]['ModelLayer']['defect_file_num'] ;
				$newData[$layer]['ModelLayer']['defect_num'] += $defact;
			}
		}
		//最後にファイル率を求める
		for ($i = 0; $i < count($newData); ++$i)
		{
			$temp = $newData[$i]['ModelLayer'];
			if($temp['all_file_num']!=0)
			{
				$newData[$i]['ModelLayer']['defect_per_file'] = 100*$temp['defect_file_num']/$temp['all_file_num'];
			}
		}
		//ファイル率計算時の0除算を防ぐため
    	return $newData;
    }

    function getLayer($filePath)
    {
    	//frameworksを含めていいのか要検討
    	//vendor/fujitsu/やbootable/bootloaderなども

    	$path = explode('/',$filePath);//先頭フォルダ名
    	$path[0] = trim($path[0]);
    	$layer= 6;

		if($path[0]=='packages')
    	{
    		$layer = 0;
    	}
    	else if($path[0]=='frameworks')//frameworksを含めていいのか要検討
    	{
    		if($path[1]=='ex' || $path[1]=='opt')
    		{
    			$layer = 1;
    		}
    		else if($path[1]=='base')
    		{
	    		switch ($path[2]) 
	    		{
	    			case 'packages':
	    				$layer = 0;
	    				break;
	    			case 'libs':
				    	$layer = 3;
				    	break;
				    default:
				    	$layer = 1;
				}
			}
    	}
    	else if($path[0]=='external')
    	{
    		$layer = 2;
    	}
    	else if($path[0]=='dalvik' || $path[0]=='libcore' || $path[0]=='system')
    	{
    		$layer = 3;
    	}
    	else if($path[0]=='hardware' || ($path[0]=='vendor' && $path[1]=='qcom' && $path[2]=='proprietary'))
    	{
    		//vendorのときはチップベンダー、製品リリースならば
    		$layer = 4;
    	}
		else if($path[0]=='kernel')
		{
			$layer = 5;
		}

		// if($layer == 6)
		// {

		// }
    	return $layer;
    }
}