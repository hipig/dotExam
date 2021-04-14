<?php

namespace App\Models;

use App\Models\Traits\OrderIndexScopeTrait;
use App\Models\Traits\StatusScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory, StatusScope, OrderIndexScopeTrait;

    const TRAIT_SPECIAL = 'special';
    const TRAIT_COMMON = 'common';
    public static $traitMap = [
        self::TRAIT_SPECIAL => '专业科目',
        self::TRAIT_COMMON => '公共科目',
    ];

    protected $fillable = [
        'parent_id',
        'title',
        'name',
        'cover',
        'trait',
        'is_directory',
        'level',
        'path',
        'status',
        'index',
    ];

    protected $casts = [
        'is_directory' => 'boolean',
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        // 监听 Subject 的创建事件，用于初始化 path 和 level 字段值
        static::creating(function (Subject $subject) {
            // 如果创建的是一个根类目
            if (is_null($subject->parent_id)) {
                // 将层级设为 0
                $subject->level = 0;
                // 将 path 设为 -
                $subject->path  = '-';
            } else {
                // 将层级设为父类目的层级 + 1
                $subject->level = $subject->parent->level + 1;
                // 将 path 值设为父类目的 path 追加父类目 ID 以及最后跟上一个 - 分隔符
                $subject->path  = $subject->parent->path.$subject->parent_id.'-';
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(Subject::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Subject::class, 'parent_id');
    }

    public function papers()
    {
        return $this->hasMany(Paper::class, 'subject_id');
    }

    public function chapterPapers()
    {
        return $this->hasMany(Paper::class, 'subject_id')->where('type', Paper::TYPE_CHAPTER);
    }

    public function mockPapers()
    {
        return $this->hasMany(Paper::class, 'subject_id')->where('type', Paper::TYPE_MOCK);
    }

    public function oldPapers()
    {
        return $this->hasMany(Paper::class, 'subject_id')->where('type', Paper::TYPE_OLD);
    }

    public function dailyPapers()
    {
        return $this->hasMany(Paper::class, 'subject_id')->where('type', Paper::TYPE_DAILY);
    }

    public function getChildrenGroupsAttribute()
    {
        return $this->children->groupBy('trait');
    }

    // 定义一个访问器，获取所有祖先类目的 ID 值
    public function getPathIdsAttribute()
    {
        // trim($str, '-') 将字符串两端的 - 符号去除
        // explode() 将字符串以 - 为分隔切割为数组
        // 最后 array_filter 将数组中的空值移除
        return array_filter(explode('-', trim($this->path, '-')));
    }

    // 定义一个访问器，获取所有祖先类目并按层级排序
    public function getAncestorsAttribute()
    {
        return Subject::query()
            // 使用上面的访问器获取所有祖先类目 ID
            ->whereIn('id', $this->path_ids)
            // 按层级排序
            ->orderBy('level')
            ->get();
    }

    // 定义一个访问器，获取以 - 为分隔的所有祖先类目名称以及当前类目的名称
    public function getFullNameAttribute()
    {
        return $this->ancestors  // 获取所有祖先类目
        ->pluck('name') // 取出所有祖先类目的 name 字段作为一个数组
        ->push($this->name) // 将当前类目的 name 字段值加到数组的末尾
        ->implode(' - '); // 用 - 符号将数组的值组装成一个字符串
    }
}
