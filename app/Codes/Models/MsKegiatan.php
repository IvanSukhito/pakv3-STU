<?php

namespace App\Codes\Models;

use Illuminate\Database\Eloquent\Model;

class MsKegiatan extends Model
{
    protected $table = 'ms_kegiatan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'parent_id',
        'name',
        'ak',
        'satuan',
        'jenjang_perancang_id',
        'permen_id',
        'status'
    ];

    public function getKegiatan() {
        return $this->hasMany(Kegiatan::class, 'ms_kegiatan_id', 'id');
    }

    public function getParent()
    {
        return $this->belongsTo(MsKegiatan::class, 'parent_id', 'id');
    }

    public function getChild()
    {
        return $this->hasMany(MsKegiatan::class, 'parent_id', 'id');
    }

    public static function getLastParent($id = null)
    {
        $parent_id = 0;
        if($id) {
            $get_list = self::where('id', $id)->first();
            if($get_list->parent_id !== 0) {
                $parent_id = self::call_last_parent($get_list);
            }
        }
        return $parent_id;
    }

    public function getJenjangPerancang()
    {
        return $this->belongsTo(JenjangPerancang::class, 'jenjang_perancang_id', 'id');
    }

    public function get_all_child($ids = []) {

    }
    public static function get_all_child_from_jenjang_perancang($jenjang_perancang_id = [])
    {
        $result = [];
        $get_list = self::where('status', 1)->whereIn('jenjang_perancang_id', $jenjang_perancang_id)->get();
        if($get_list) {
            foreach($get_list as $list) {
                $result[] = $list->id;
                $get_parent = $list->getParent()->first();
                $result[$list->id] = $get_parent->id;
                $temp = [];
                if($get_parent) {
                    $temp = self::call_parent($get_parent);
                }
                $result = array_merge($result, $temp);
            }
        }
        sort($result);
        $result = array_unique($result);

        $get_list = self::where('status', 1)->whereIn('id', $result)->get();

        return $get_list;
    }

    public static function get_all_child_from_ids($ids = [])
    {
        $result = [];
        $get_list = self::where('status', 1)->whereIn('id', $ids)->get();
   
        if($get_list) {
            foreach($get_list as $list) {
                $result[] = $list->id;
                $get_parent = $list->getParent()->first();
              //  $get_parent = $list->parent_id;
                $result[$list->id] = isset($get_parent->id) ? $get_parent->id : ' ';
                $temp = [];
                if($get_parent) {
                    $temp = self::call_parent($get_parent);
                }
                $result = array_merge($result, $temp);
            }
        }
        sort($result);
        $result = array_unique($result);

        $get_list = self::where('status', 1)->whereIn('id', $result)->get();

        return $get_list;
    }

    protected static function call_parent($child = null) {
        
        $result = [];
        $get_parent = $child->getParent()->first();

        if($get_parent) {
            $result[$get_parent->id] = $get_parent->id;
            $temp = self::call_parent($get_parent);
            $result = array_merge($result, $temp);
        }
        return $result;
    }

    protected static function call_last_parent($child) {
        $get_parent = $child->getParent()->first();
        if($get_parent) {
            if($get_parent->parent_id !== 0) {
                $parent_id = self::call_last_parent($get_parent);
            }
            else {
                $parent_id = $get_parent->id;
            }
            return $parent_id;
        }
        return $child->id;
    }

}
