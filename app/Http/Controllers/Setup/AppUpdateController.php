<?php

namespace App\Http\Controllers\Setup;

use App\Exceptions\GeneralException;
use App\Helpers\Traits\SetIiiTrait;
use App\Http\Controllers\Controller;
use App\Setup\Helper\PermissionsHelper;
use App\Setup\Helper\UrlHelper;
use App\Setup\Helper\ValidatePHPVersion;
use App\Setup\Manager\FileManager;
use App\Setup\Manager\FinalInstallManager;
use App\Setup\Manager\UpdateManager;
use Illuminate\Support\Facades\Artisan;

class AppUpdateController extends Controller
{
    use UrlHelper, SetIiiTrait, ValidatePHPVersion;

    protected $permission;
    protected $updateManager;
    protected $fileManager;
    protected $manager;

    public function __construct(
        PermissionsHelper $permissionsHelper,
        UpdateManager $updateManager,
        FileManager $fileManager,
        FinalInstallManager $manager
    )
    {
        $this->permission = $permissionsHelper;
        $this->updateManager = $updateManager;
        $this->fileManager = $fileManager;
        $this->manager = $manager;
    }

    public function index()
    {
        throw_if(
            $this->permission->check(['public/' => 'writeable', '/' => 'writeable'])['errors'],
            new GeneralException(trans('default.public_directory_must_be_writeable_to_update_the_app'))
        );
        $updates = $this->updateManager->updates();

        if ($updates->status) {
            $spliceIndex = array_search(config('gain.app_version'), array_column((array)$updates, 'version'));

            $result = collect($updates->result)->map(function ($version) {
                $version->version = str_replace('.zip', '', $version->version);
                return $version;
            })->toArray();

            if (!$spliceIndex)
                return response()->json(['status' => true, 'result' => $result]);

            $result = collect($result)->filter(function ($value, $index) use ($spliceIndex) {
                return $index >= $spliceIndex;
            })->toArray();

            return response()->json(['status' => true, 'result' => $result]);

        }
        return response()->json((array)$updates, 402);

    }

    public function update($version)
    {
        $this->fileManager->removeCachedFile();
        $this->setMemoryLimit();
        $this->setExecutionTime();
        $this->validatePHPVersion();

        throw_if(
            !array_search('zip', get_loaded_extensions()),
            new GeneralException(trans('default.install_zip_extension'), 404)
        );

        $nextVersion = $this->getNextReadyToInstallVersion($version);

        throw_if(
            !$nextVersion['check'],
            new GeneralException(trans('default.please_install_version_first', ['number' => $nextVersion['version']]))
        );

        $this->fileManager->extract($version);

        $this->manager->finishUpdate();

        return response()->json(['status' => true, 'message' => "$version installed successfully."]);
    }

    public function getNextReadyToInstallVersion($version)
    {
        $available_updates = $this->index()->getData();


        throw_if(!$available_updates->status, new GeneralException(trans('default.invalid_purchase_code')));

        $updates = $available_updates->result;

        $installable = array_search($version, array_column($updates, 'version'));
        if ($installable === 0)
            return ['version' => $updates[$installable]->version, 'check' => true];

        return ['version' => $updates[0]->version, 'check' => false];
    }

}
