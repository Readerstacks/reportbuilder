<?php

namespace Aman5537jains\Blocks;

use Aman5537jains\Blocks\Model\BlockManagerSetting;
use Aman5537jains\Blocks\Model\BlockManagerSettingOption;
use Aman5537jains\CmsManager\CmsManager;
use Illuminate\View\Component;

class BlockComponent extends Component
{
    /**
     * The alert type.
     *
     * @var string
     */
    public $col;
    public $uniqueid;

    /**
     * The alert message.
     *
     * @var string
     */
    public $message;
    public $id;

    /**
     * Create the component instance.
     *
     * @param string $type
     * @param string $message
     *
     * @return void
     */
    public function __construct($uniqueid)
    {
        $this->uniqueid = $uniqueid;

        // $this->message = $message;
        // $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        $component = CmsManager::getComponent($this->uniqueid);

        if ($component->relative_id > 0) {
            $BlockManagerSetting = BlockManagerSetting::find($component->relative_id);
            $BlockManagerSettingOption = BlockManagerSettingOption::where('block_manager_id', $BlockManagerSetting->id)->get();
        }

        $this->col = (int) $BlockManagerSetting->number_of_columns;
        $component = '';
        if ($BlockManagerSetting->component > 0) {
            $component = CmsManager::getComponent($BlockManagerSetting->component);
        //    dd( $component);
        }

        return view('BlocksManager::check2', ['BlockManagerSettingOption'=>$BlockManagerSettingOption, 'component'=>$component]);
    }
}
