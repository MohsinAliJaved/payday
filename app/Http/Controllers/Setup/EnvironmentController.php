<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Models\Core\Auth\User;
use App\Services\Core\Auth\UserService;
use App\Setup\Helper\PermissionsHelper;
use App\Setup\Helper\Requirements;
use App\Setup\Manager\DatabaseManager;
use App\Setup\Manager\EnvironmentManager;
use App\Setup\Manager\FinalInstallManager;
use App\Setup\Manager\PurchaseCodeManager;
use App\Setup\Manager\StorageManager;
use App\Setup\Validator\PurchaseCodeValidator;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class EnvironmentController extends Controller
{
    protected $requirement;
    protected $permission;
    protected $environment;
    protected $userService;
    protected $purchaseCodeValidator;
    protected $databaseManager;
    protected $purchaseCodeManager;
    protected $manager;
    protected $storage;

    public function __construct(
        Requirements $requirements,
        PermissionsHelper $permission,
        EnvironmentManager $environment,
        UserService $userService,
        PurchaseCodeValidator $codeValidator,
        DatabaseManager $databaseManager,
        PurchaseCodeManager $codeManager,
        FinalInstallManager $manager,
        StorageManager $storage
    ){
        $this->requirement = $requirements;
        $this->permission = $permission;
        $this->environment = $environment;
        $this->userService = $userService;
        $this->purchaseCodeValidator = $codeValidator;
        $this->databaseManager = $databaseManager;
        $this->purchaseCodeManager = $codeManager;
        $this->manager = $manager;
        $this->storage = $storage;
    }

    public function index()
    {
        return view('install.environment');
    }

    public function update()
    {
        $this->attributesValidations();

        if ($this->purchaseCodeValidator->validate(request()->get('code'))){
            try {
                $this->manager->clear();

                $this->databaseManager->setConfig();

                $this->environment
                    ->saveFileWizard(request());

                $this->databaseManager->migrate();

                $this->purchaseCodeManager->store(
                    request()->get('code')
                );

                $this->databaseManager->seed();

                $user = User::query()->find(1);

                $user->update([
                   'first_name' => request()->get('first_name'),
                   'last_name'  => request()->get('last_name'),
                   'email' => request()->get('email'),
                   'password' => Hash::make(request()->get('password'))
                ]);

                $this->environment->setEnvironmentValue('APP_INSTALLED', 'true');

                $this->manager->finish();

                $this->storage->link();

                return response()->json(['status' => true, 'message' => trans('default.app_installed_successfully')]);
            }catch (\Exception $exception) {
                return response()->json(['status' => false, 'message' => $exception->getMessage()], 424);
            }
        }
        throw ValidationException::withMessages([
            'code' => [trans('default.invalid_purchase_code')]
        ]);
    }

    public function attributesValidations(): self
    {
        validator(request()->all(), [
            'database_connection' => 'required',
            'database_hostname' => 'required',
            'database_port' => 'required',
            'database_name' => 'required',
            'database_password' => 'required',
            'database_username' => 'required',
            'email' => 'required',
            'full_name' => 'required',
            'password' => ['required', 'confirmed', 'regex:/^(?=[^\d]*\d)(?=[A-Z\d ]*[^A-Z\d ]).{8,}$/i'],
            'code' => 'required',
        ])->validate();

        return $this;
    }
}
