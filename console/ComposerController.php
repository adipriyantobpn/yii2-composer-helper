<?php
/**
 * User: Adi Priyanto
 * Date: 3/9/2017
 * Time: 7:55 PM
 */

namespace adipriyantobpn\console;


use yii\console\Controller;
use yii;

class ComposerController extends Controller
{
    const FORMAT_JSON = 'json';
    const FORMAT_TEXT = 'text';

    protected $workDir;
    protected $gitDir;
    protected $composerCmd;
    protected $gitCmd;

    /**
     * @var bool increase the verbosity of messages, as in composer --verbose
     */
    public $verbose = 1;
    /**
     * @var bool debug the system shell command executed by this console application
     */
    public $debug = 1;

    public $file;

    /**
     * Prepare file/directory path into shell command friendly format
     *
     * @param string $path File/directory path
     * @return string File/directory path which is shell command friendly format
     */
    protected function preparePathForShellCmd($path)
    {
        return addslashes(realpath($path));
    }

    /**
     * Create specified directory if not exists
     *
     * @param string $directoryAlias Directory path in Yii2 alias format
     */
    protected function prepareDirectory($directoryAlias)
    {
        if (!file_exists($dirPath = Yii::getAlias($directoryAlias, false))) {
            mkdir($dirPath, 0777, true);
        }
    }

    /**
     * Execute the shell command, and do debugging if needed
     *
     * @param string $cmd Shell command
     * @param string|array|null $output Output from executed shell command
     * @param string|integer|null $returnVal Return value from executed shell command
     */
    protected function executeShell($cmd, &$output = null, &$returnVal = null)
    {
        $cmd = escapeshellcmd($cmd);
        exec($cmd, $output, $returnVal);
        $this->debug($cmd, $output, $returnVal);
    }

    /**
     * Debug the executed shell command
     *
     * @param string $execCmd Shell command
     * @param string|array|null $execOutput Output from executed shell command
     * @param string|integer|null $execReturnValue Return value from executed shell command
     */
    protected function debug($execCmd, $execOutput, $execReturnValue)
    {
        if ($this->debug) {
            echo "\n-----------\n";
            echo "-- DEBUG --";
            echo "\n-----------\n";
            echo "Executed command : \n=> ";
            echo is_string($execCmd) ? $execCmd : yii\helpers\VarDumper::dumpAsString($execCmd);
            echo "\n-----------\n";
            echo "Process output : \n=> ";
            echo is_string($execOutput) ? $execOutput : yii\helpers\VarDumper::dumpAsString($execOutput);
            echo "\n-----------\n";
            echo "Is process failed? (0 = no, non-zero = yes) : \n=> ";
            echo is_string($execReturnValue) ? $execReturnValue : yii\helpers\VarDumper::dumpAsString($execReturnValue);
            echo "\n-----------\n";
        }
    }

    /**
     * This method is invoked right before an action is executed.
     *
     * The method will trigger the [[EVENT_BEFORE_ACTION]] event. The return value of the method
     * will determine whether the action should continue to run.
     *
     * In case the action should not run, the request should be handled inside of the `beforeAction` code
     * by either providing the necessary output or redirecting the request. Otherwise the response will be empty.
     *
     * If you override this method, your code should look like the following:
     *
     * ```php
     * public function beforeAction($action)
     * {
     *     // your custom code here, if you want the code to run before action filters,
     *     // which are triggered on the [[EVENT_BEFORE_ACTION]] event, e.g. PageCache or AccessControl
     *
     *     if (!parent::beforeAction($action)) {
     *         return false;
     *     }
     *
     *     // other custom code here
     *
     *     return true; // or false to not run the action
     * }
     * ```
     *
     * @param yii\base\Action $action the action to be executed.
     * @return bool whether the action should continue to run.
     */
    public function beforeAction($action)
    {
        $this->workDir = $this->preparePathForShellCmd(dirname(Yii::getAlias('@app')));
        $this->gitDir = $this->preparePathForShellCmd(dirname(Yii::getAlias('@app')).'/.git');
        $this->verbose = $this->verbose ? '-vvv' : '';

        $this->composerCmd = "composer --working-dir=\"{$this->workDir}\" {$this->verbose} ";
        $this->gitCmd = "git --git-dir=\"{$this->gitDir}\" --work-tree=\"{$this->workDir}\" ";

        if (!parent::beforeAction($action)) {
            return false;
        }
        return true;
    }

    /**
     * Returns the names of valid options for the action (id)
     * An option requires the existence of a public member variable whose
     * name is the option name.
     * Child classes may override this method to specify possible options.
     *
     * Note that the values setting via options are not available
     * until [[beforeAction()]] is being called.
     *
     * @param string $actionID the action id of the current request
     * @return string[] the names of the options valid for the action
     */
    public function options($actionID)
    {
        return yii\helpers\ArrayHelper::merge(parent::options($actionID), [
            'verbose',
            'debug',
        ]);
    }

    /**
     * Returns option alias names.
     * Child classes may override this method to specify alias options.
     *
     * @return array the options alias names valid for the action
     * where the keys is alias name for option and value is option name.
     *
     * @since 2.0.8
     * @see options()
     */
    public function optionAliases()
    {
        return yii\helpers\ArrayHelper::merge(parent::optionAliases(), [
            'v' => 'verbose',
            'd' => 'debug',
        ]);
    }

    /**
     * Export all packages which specified in the composer.json
     *
     * @param string $format Format of the output: text or json
     * @param string|null $file File path for saving exported data, also support Yii2 path alias
     * @return string Return back the exported file path
     */
    public function actionExportRootPackages($format = self::FORMAT_JSON, $file = '@common/runtime/composer-packages-root.json')
    {
        if ($file != '') {
            $this->prepareDirectory(dirname(Yii::getAlias($file)));
            $fileString = ' > "' . $this->preparePathForShellCmd(Yii::getAlias($file)) . '"';
        } else {
            throw new yii\console\Exception('File path is not valid');
        }
        $execCmd = $this->composerCmd . "show --no-ansi --direct --format={$format} {$fileString}";
        $this->executeShell($execCmd, $composerMsgs, $composerReturnVal);

        return $file;
    }

    /**
     * Export all installed packages
     *
     * @param bool $asTree List the dependencies as a tree
     * @param string $file File path for saving exported data, also support Yii2 path alias
     * @param string $format Format of the output: text or json
     * @throws yii\console\Exception
     */
    public function actionExportInstalledPackages($asTree = false, $file = '@common/runtime/composer-packages.txt', $format = 'text')
    {
        if ($file != '') {
            $this->prepareDirectory(dirname(Yii::getAlias($file)));
        } else {
            throw new yii\console\Exception('File path is not valid');
        }

        if ($asTree && $format != 'text') {
            throw new yii\console\Exception('Exporting packages as tree can only be processed in text format');
        }

        if ($asTree) {
            //-- export all root packages as JSON format
            $rootPackageFilePath = $this->actionExportRootPackages(self::FORMAT_JSON);
            //-- read the root packages
            $rootPackageFile = file_get_contents(Yii::getAlias($rootPackageFilePath));
            $json = yii\helpers\Json::decode($rootPackageFile);
            $pkgs = yii\helpers\ArrayHelper::getColumn($json['installed'], 'name');
            //-- export bash script to execute `composer show`
            $bashFilePathAlias = '@common/runtime/script-composer-packages-root-exporter.sh';
            $bashFilePath = Yii::getAlias($bashFilePathAlias);
            if ( file_exists($bashFilePath) ) { unlink($bashFilePath); }
            $bashFile = fopen($bashFilePath, 'w+');
            $cmd = $this->composerCmd . "show --tree --no-ansi ";
            $dest = $this->preparePathForShellCmd(Yii::getAlias($file));
            fwrite($bashFile, "#!/bin/bash\n\n");
            foreach($pkgs as $i => $pkg) {
                $opr = ($i == 0) ? ' > ' : ' >> ';
                $execCmd = $cmd . $pkg . $opr . '"' . $dest . '"';
                // -- execute bash command while writing them in the file
                $this->executeShell($execCmd, $composerMsgs, $composerReturnVal);
                fwrite($bashFile, $execCmd . "\n");
            }
            fclose($bashFile);

        } else {
            $file = ' > "' . $this->preparePathForShellCmd(Yii::getAlias($file)) . '"';
            $execCmd = $this->composerCmd . "show --no-ansi --format={$format} {$file}";
            $this->executeShell($execCmd, $composerMsgs, $composerReturnVal);
        }
    }

    /**
     * Export all available Composer packages, which are registered in packagist.org
     *
     * @param string $file File path for saving exported data, also support Yii2 path alias
     * @throws yii\console\Exception
     */
    public function actionExportAllAvailablePackages($file = '@common/runtime/composer-packages-all-available.txt')
    {
        if ($file != '') {
            $this->prepareDirectory(dirname(Yii::getAlias($file)));
            $file = ' > "' . $this->preparePathForShellCmd(Yii::getAlias($file)) . '"';
        } else {
            throw new yii\console\Exception('File path is not valid');
        }
        $execCmd = $this->composerCmd . "show --no-ansi --available {$file}";
        $this->executeShell($execCmd, $composerMsgs, $composerReturnVal);
    }

    /**
     * Batch export for all supported data,
     * including installed packages (as text & as tree) specified in the composer.json,
     * and all available packages from packagist.org
     *
     * @param string $installedPackageFilePath
     * @param string $installedPackageAsTreeFilePath
     * @param string $allAvailablePackageFilePath
     */
    public function actionBatchExport(
        $installedPackageFilePath = '@common/runtime/composer-packages.txt',
        $installedPackageAsTreeFilePath = '@common/runtime/composer-packages-tree.txt',
        $allAvailablePackageFilePath = '@common/runtime/composer-packages-all-available.txt'
    )
    {
        $this->actionExportInstalledPackages(0, $installedPackageFilePath);
        $this->actionExportInstalledPackages(1, $installedPackageAsTreeFilePath);
        $this->actionExportAllAvailablePackages($allAvailablePackageFilePath);
    }

    /**
     * Install packages via Composer, export the packages list, and commit them using git
     *
     * @param string $packageName Package name
     * @param bool $preferSource Whether installing the package sources, or just install the package distribution
     * @param bool $commit Whether commit the package list using git
     */
    public function actionRequirePackages($packageName, $preferSource = true, $commit = false)
    {
        $preferSourceString = $preferSource ? '--prefer-source' : '--prefer-dist';

        $execCmd = $this->composerCmd . "require --no-ansi {$preferSourceString} {$packageName}";
        $this->executeShell($execCmd, $composerMsgs, $composerReturnVal);

        if ($composerReturnVal === 0) {
            $this->actionBatchExport();
            if ($commit) {
                $execCmd = $this->gitCmd . "add .";
                $this->executeShell($execCmd, $gitMsgs, $gitReturnVal);

                $execCmd = $this->composerCmd . "show --no-ansi {$packageName}";
                $this->executeShell($execCmd, $commitMsgs, $composerReturnVal);

                if ($composerReturnVal === 0) {
                    $execCmd = $this->gitCmd . "commit " . '--message="' . implode('" --message="', array_filter($commitMsgs)) . '"';
                    $this->executeShell($execCmd, $gitMsgs, $gitReturnVal);
                }
            }
        }
    }
}