<?php


namespace Toolbox\Core\Component;


use Bitrix\Main\Application;
use CBitrixComponent;
use CComponentEngine;
use Toolbox\Core\ContainerTrait;
use Toolbox\Core\Logger\LoggerTrait;
use CModule;
use Toolbox\Core\Container;
use Psr\Container\ContainerInterface;

abstract class BaseComponent extends CBitrixComponent
{
    use ContainerTrait;
    use LoggerTrait;
    protected $sefFolder = '/';

    public function getRequest()
    {
        return Application::getInstance()->getContext()->getRequest();
    }

    public function isAjaxRequest()
    {
        $request = $this->getRequest();
        return $request->isAjaxRequest();
    }

    /** @deprecated use method jsonResponse
     * @param array $data
     */
    public function sendJson(array $data = [])
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        exit(json_encode($data));
    }

    public function jsonResponse(array $data = [])
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        exit(json_encode($data));
    }


    /**
     * шаблоны путей по умолчанию
     * @var array
     */
    protected $defaultUrlTemplates404 = array();

    /**
     * переменные шаблонов путей
     * @var array
     */
    protected $componentVariables = array();

    /**
     * страница шаблона
     * @var string
     */
    protected $page = '';

    /**
     * определяет переменные шаблонов и шаблоны путей
     */
    protected function setSefDefaultParams()
    {
        $this->defaultUrlTemplates404 = array(
            'index' => 'index.php',
            'user' => 'user/#ELEMENT_ID#/',
            'admin' => 'admin/',
            'search' => 'search/'
        );
        $this->componentVariables = array('ELEMENT_ID');
    }

    /**
     * получение результатов
     */
    protected function getResult()
    {
        $urlTemplates = array();
        if ($this->arParams['SEF_MODE'] == 'Y') {
            $variables = array();
            $urlTemplates = \CComponentEngine::MakeComponentUrlTemplates(
                $this->defaultUrlTemplates404,
                $this->arParams['SEF_URL_TEMPLATES']
            );
            $variableAliases = \CComponentEngine::MakeComponentVariableAliases(
                $this->defaultUrlTemplates404,
                $this->arParams['VARIABLE_ALIASES']
            );
            $engine = new CComponentEngine($this);

            if (CModule::IncludeModule('iblock')) {
                $engine->addGreedyPart("#SECTION_CODE_PATH#");
                $engine->setResolveCallback(array(
                    "CIBlockFindTools",
                    "resolveComponentEngine"));
            }

            $this->page = $engine->guessComponentPath(
                $this->arParams['SEF_FOLDER'],
                $urlTemplates,
                $variables
            );

            if (strlen($this->page) <= 0)
                $this->page = 'index';

            \CComponentEngine::InitComponentVariables(
                $this->page,
                $this->componentVariables,
                $variableAliases,
                $variables
            );
        } else {
            $this->page = 'index';
        }

        $this->arResult = array(
            'FOLDER' => $this->arParams['SEF_FOLDER'],
            'URL_TEMPLATES' => $urlTemplates,
            'VARIABLES' => $variables,
            'ALIASES' => $variableAliases
        );
    }

    /**
     * @param  string $services
     * @return mixed
     */
    protected function get($services)
    {
        $container = $this->getContainer();
        return $container->get($services);
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return Container::getInstance();
    }
    /**
     * @param int $totalRows
     * @param int $currentPage
     */
    protected function createPagination($totalRows, $currentPage)
    {
        $dbResult = new \CDBResult();
        $dbResult->NavStart($this->arParams['PAGE_ELEMENT_COUNT']);
        $dbResult->NavPageCount = ceil($totalRows / $this->arParams['PAGE_ELEMENT_COUNT']);
        $dbResult->NavPageNomer = $currentPage;
        $dbResult->NavRecordCount = $totalRows;

        $this->arResult['NAV_STRING'] = $dbResult->GetPageNavStringEx($navComponentObject, '', $this->arParams['PAGER_TEMPLATE']);
    }
    /**
     * выполняет логику работы компонента
     */
    public function executeComponent()
    {
        try {
            $this->setSefDefaultParams();
            $this->getResult();
            $this->includeComponentTemplate($this->page);


        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }

    /**
     * @return array
     */
    public function getDefaultUrlTemplates404()
    {
        return $this->defaultUrlTemplates404;
    }

    public function getSefFolder()
    {
        return $this->arParams['SEF_FOLDER'] ? $this->arParams['SEF_FOLDER'] : $this->sefFolder;
    }

    public function getUrlByAliasTemplate($alias, $params)
    {
        if ($template = $this->defaultUrlTemplates404[$alias]){
            return $this->getSefFolder() . str_replace(array_keys($params) , array_values($params), $template );
        }
        throw new \Exception('Template for alias "'. $alias .'" not exist');

    }
}