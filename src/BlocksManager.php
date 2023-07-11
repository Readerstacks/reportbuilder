<?php

namespace Aman5537jains\Blocks;

use Aman5537jains\Blocks\Model\BlockManagerSetting;
use Aman5537jains\Blocks\Model\BlockManagerSettingOption;
use Aman5537jains\CmsManager\CmsManager;
use Illuminate\Http\Request;

class BlocksManager
{
    public function configure(Request $request)
    {
        $cid = $request->component_id;

        $component = CmsManager::getComponent($request->component_id);
        $components = CmsManager::componentList();
        if ($component->relative_id > 0) {
            $BlockManagerSetting = BlockManagerSetting::find($component->relative_id);

            $BlockManagerSettingOption = BlockManagerSettingOption::where('block_manager_id', $BlockManagerSetting->id)->get();

            return view('BlocksManager::configure', ['component_id'=> $request->component_id,
                'BlockManagerSettingOption'                        => $BlockManagerSettingOption, 'BlockManagerSetting'=>$BlockManagerSetting, 'components'=>$components]);
        }

        return view('BlocksManager::configure', ['component_id'=>$request->component_id, 'components'=>$components]);
    }
}
