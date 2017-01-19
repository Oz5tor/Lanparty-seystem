<?php
require_once("class/Bytes.php"); // to get file bytes
function ImageUploade($InputName, $TargetDir, $AllowedFileTypeArray)
{
    
    $file_name = $_FILES["$InputName"]['name'];
    $file_size =$_FILES["$InputName"]['size'];
    $file_tmp  =$_FILES["$InputName"]['tmp_name'];
    $file_type =$_FILES["$InputName"]['type'];
    $onlyfiletype = explode('.',strtolower($_FILES["$InputName"]['name']));
    $file_ext= end($onlyfiletype);
    
    if($_FILES["$InputName"]['error'] >= 1)
    {
        switch($_FILES["$InputName"]['error'])
        {
            default:
                return false;
                break;
        }
    }else if(in_array($file_ext,$AllowedFileTypeArray)=== false)
    {
        return false; // hvis fil extention ikke er i arrayet
    }else if($file_size > return_bytes(ini_get('upload_max_filesize')))
    {
        return false;// file size too big
    }else{
        $FileName = str_replace(' ', '_', $file_name);
        $FileName = time().'_'.$FileName;
        //$Path = $TargetDir.'/'.$FileName; // not in use
        move_uploaded_file($file_tmp,$TargetDir.'/'.$FileName);
        return $FileName;
        }
}
?>