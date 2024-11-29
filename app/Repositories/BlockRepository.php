<?php

namespace App\Repositories;

use App\Models\Block;

class BlockRepository
{
    protected $model;

    public function __construct(Block $model)
    {
        $this->model = $model;
    }

    public static function getVisibleBlocks($region)
    {
        $all_blocks = Block::isPublished()->where('region', $region)->get();

        $blocks = $all_blocks->reject(function ($block) {
            $conditions = $block->conditions;
            $conditions = str_replace("\r", '', $conditions);
            $conditions = explode("\n", $conditions);

            if (!\Request::is(...$conditions)) {
                return $block;
            }
        });

        return $blocks;
    }

    public static function getRegions()
    {
        $regions = [
            'header' => 'Header',
            'page-top' => 'Page Top',
            'page-bottom' => 'Page Bottom',
            'sidebar-left' => 'Sidebar Left',
            'sidebar-right' => 'Sidebar Right',
        ];

        /*
            messages: 'Messages'
          header_first: 'Header first'
          header_second: 'Header second'
          header_third: 'Header third'
          navbar: 'Nav bar'
          features_first: 'Features first'
          features_second: 'Features second'
          features_third: 'Features third'
          features_fourth: 'Features fourth'
          highlighted: 'Highlighted'
          content: 'Main content'
          sidebar_first: 'Sidebar first'
          sidebar_second: 'Sidebar second'
          tertiary_first: 'Tertiary first'
          tertiary_second: 'Tertiary second'
          tertiary_third: 'Tertiary third'
          tertiary_fourth: 'Tertiary fourth'
          footer: 'Footer'
          page_top: 'Page top'
          page_bottom: 'Page bottom'
          */

        // $regions = array_combine($regions, $regions);
        $regions = ['' => '-Select Region-'] + $regions;

        return $regions;
    }

    public function getBlock($id)
    {
        $block = $this->model->findOrFail($id);

        return $block;
    }
}
