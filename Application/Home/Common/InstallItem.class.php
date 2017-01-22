<?php
namespace Home\Common;
/**
 * Created by PhpStorm.
 * User: PC-HT
 * Date: 2017/1/13
 * Time: 1:12
 */
class InstallItem
{
    var $name;
    var $type_id;
//    var $add_menu;
    var $items = array();

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function setTypeId($typeID){
        $this->type_id = $typeID;
    }

//    public function setAddMenuStatus($status){
//        $this->add_menu = $status;
//    }

    /**
     * @param array $item
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

}