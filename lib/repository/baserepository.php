<?php


namespace Toolbox\Core\Repository;


use function Bitrix\Main\__autoload;
use Bitrix\Main\Entity\AddResult;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DeleteResult;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Entity\ScalarField;
use Bitrix\Main\Entity\UpdateResult;
use CIBlockProperty;
use CIBlockPropertyEnum;
use Toolbox\Core\Logger\LoggerTrait;
use Toolbox\Core\Util\Lang;
use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionClass;

abstract class BaseRepository implements EntityRepositoryInterface, RepositoryInterface, RepositoryCriteriaInterface
{
    use LoggerTrait;
    use Lang;

    /**
     * @var array
     */
    protected $fieldSearchable = array();

    /**
     * @var CriteriaInterface[]
     */
    protected $criteria;

    /**
     * @var DataManager
     */
    protected $entity;

    /**
     * @var bool
     */
    protected $skipCriteria = false;

    /**
     * @var array
     */
    private $parameters = array();
    private $isNew = false;
    protected $reflector;

    /**
     * Construct object
     */
    public function __construct()
    {
        $this->criteria = array();
        $this->makeEntity();
        $this->boot();
    }

    /**
     * Boot example:
     *
     * $this->pushCriteria(new MyCriteria());
     * $this->pushCriteria(new AnotherCriteria());
     *
     */
    public function boot()
    {

    }

    public function resetEntity()
    {
        $this->makeEntity();
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function makeEntity()
    {
        $entity = $this->getEntity();

        if (!$entity instanceof DataManager) {
            throw new \Exception('Class must be an instance of Bitrix\Main\Entity\DataManager');
        }

        $this->entity = $entity;
        $this->parameters = array();

        return $this;
    }

    /**
     * Retrieve all data of repository
     *
     * @param  array $columns
     * @return mixed
     */
    public function all($columns = array())
    {
        $this->applyCriteria();

        $parameters = $this->getParameters();
        if (!empty($columns)) {
            $parameters['select'] = $columns;
        }

        $result = $this->entity
            ->getList($parameters)
            ->fetchAll();

        $this->resetEntity();

        return $result;
    }

    /**
     * Find data by id
     *
     * @param $id
     * @param  array $columns
     * @throws \Exception
     * @return mixed
     */
    public function find($id, $columns = array())
    {
        $this->applyCriteria();

        $primary = $this->entity->getEntity()
            ->getPrimaryArray();
        if (empty($primary)) {
            throw new \Exception('Entity not found primary field');
        }

        $primary = array_shift($primary);
        $parameters = $this->getParameters();
        $parameters['filter'][sprintf('=%s', $primary)] = $id;

        if (!empty($columns)) {
            $parameters['select'] = $columns;
        }

        $result = $this->entity
            ->getList($parameters)
            ->fetch();

        $this->resetEntity();

        return $result;
    }

    /**
     * Find data by field and value
     *
     * @param $field
     * @param $value
     * @param  array $columns
     * @return mixed
     */
    public function findByField($field, $value, $columns = array())
    {
        $this->applyCriteria();

        $parameters = $this->getParameters();
        if (!empty($columns)) {
            $parameters['select'] = $columns;
        }
        $parameters['filter'][$field] = $value;
        $result = $this->entity
            ->getList($parameters)
            ->fetchAll();

        $this->resetEntity();

        return $result;
    }

    /**
     * Find data by multiple fields
     *
     * @param  array $where
     * @param  array $columns
     * @param  array $order
     * @param int $offset
     * @param  int $limit
     * @return mixed
     */
    public function findWhere(array $where, $columns = array(), $order = array(), $offset = 0, $limit = 0)
    {

        $this->applyCriteria();

        $parameters = $this->getParameters();
        $parameters['filter'] = array_merge(
            (array)$parameters['filter'],
            (array)$where
        );

        if (!empty($columns)) {
            $parameters['select'] = $columns;
        }

        if (!empty($order)) {
            $parameters['order'] = $order;
        }

        if ($limit > 0) {
            $parameters['limit'] = $limit;
        }

        if ($offset > 0) {
            $parameters['offset'] = $offset;
        }

        $result = $this->entity
            ->getList($parameters)
            ->fetchAll();

        $this->resetEntity();

        return $result;
    }

    /**
     * Find data by multiple fields
     *
     * @param  array $where
     * @param  array $columns
     * @param  array $order
     * @return mixed
     */
    public function findOneWhere(array $where, $columns = array(), $order = array())
    {
        $this->applyCriteria();

        $parameters = $this->getParameters();
        $parameters['filter'] = array_merge(
            (array)$parameters['filter'],
            (array)$where
        );

        if (!empty($columns)) {
            $parameters['select'] = $columns;
        }
        if (!empty($order)) {
            $parameters['order'] = $order;
        }

        $result = $this->entity
            ->getList($parameters)
            ->fetch();

        $this->resetEntity();

        return $result;
    }

    /**
     * Find data by multiple values in one field
     *
     * @param $field
     * @param  array $values
     * @param  array $columns
     * @return mixed
     */
    public function findWhereIn($field, array $values, $columns = array('*'))
    {
        $this->applyCriteria();

        $parameters = $this->getParameters();
        if (!empty($columns)) {
            $parameters['select'] = $columns;
        }

        $parameters['filter'][$field] = $values;
        $result = $this->entity
            ->getList($parameters)
            ->fetchAll();

        $this->resetEntity();

        return $result;
    }

    /**
     * Find data by excluding multiple values in one field
     *
     * @param $field
     * @param  array $values
     * @param  array $columns
     * @return mixed
     */
    public function findWhereNotIn($field, array $values, $columns = array('*'))
    {
        $this->applyCriteria();

        $parameters = $this->getParameters();
        if (!empty($columns)) {
            $parameters['select'] = $columns;
        }

        $parameters['filter'][sprintf('!=%s', $field)] = $values;
        $result = $this->entity
            ->getList($parameters)
            ->fetchAll();

        $this->resetEntity();

        return $result;
    }

    /**
     * Push Criteria for filter the query
     *
     * @param  CriteriaInterface $criteria
     * @return $this
     */
    public function pushCriteria(CriteriaInterface $criteria)
    {
        array_push($this->criteria, $criteria);

        return $this;
    }

    /**
     * Resets Criteria
     *
     * @return $this
     */
    public function resetCriteria()
    {
        $this->criteria = array();

        return $this;
    }

    /**
     * Apply criteria in current Query
     * @throws \Exception
     * @return $this
     */
    protected function applyCriteria()
    {
        if ($this->skipCriteria === true) {
            $this->parameters = array();
            return $this;
        }

        $criteriaList = $this->getCriteria();
        if (!sizeof($criteriaList)) {
            return $this;
        }

        foreach ($criteriaList as $criteria) {
            if ($criteria instanceof CriteriaInterface) {
                $this->parameters = $criteria->apply($this->parameters, $this);
            }
        }

        $defaultParams = $this->getDefaultParameters();
        foreach ($this->parameters as $param => $value) {
            if (!array_key_exists($param, $defaultParams)) {
                throw new \Exception(sprintf('Criteria return invalid parameter %s', $param));
            }
        }

        return $this;
    }

    /**
     * @param  array $attributes
     * @param  array|int $primary
     * @return UpdateResult
     */
    public function update($primary, array $attributes)
    {
        return $this->entity->update($primary, $attributes);
    }

    /**
     * @param  array $attributes
     * @return AddResult
     */
    public function add(array $attributes)
    {
        return $this->entity->add($attributes);
    }

    /**
     * @param  array|int $primary
     * @return DeleteResult
     */
    public function delete($primary)
    {
        return $this->entity->delete($primary);
    }

    /**
     * Get Collection of Criteria
     *
     * @return array
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @return array
     */
    private function getDefaultParameters()
    {
        return array(
            'select' => array(),
            'filter' => array(),
            'group' => array(),
            'order' => array(),
            'limit' => null,
            'offset' => null,
            'count_total' => null,
            'runtime' => array(),
            'data_doubling' => false,
        );
    }

    /**
     * @param  string $type
     * @throws \Exception
     * @return mixed
     */
    protected function getParameterByType($type)
    {
        $defaultParams = $this->getDefaultParameters();
        if (!array_key_exists($type, $defaultParams)) {
            throw new \Exception('Unknown parameter used: ' . implode(',', array_keys($defaultParams)));
        }

        return $this->parameters[$type] ?: $defaultParams[$type];
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Skip Criteria
     *
     * @param  bool $status
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;

        return $this;
    }

    /**
     * @param $pageSize
     * @param $curPage
     * @param array $select
     * @param array $filter
     * @return mixed
     */
    public function getElementsPerPage($pageSize, $curPage, $select = array('*'), $filter = array())
    {
        $this->applyCriteria();

        $parameters = $this->getParameters();
        $parameters['select'] = $select;
        if (!empty($filter)) {
            $parameters['filter'] = $filter;
            $res['COUNT'] = $this->getEntityCount($filter);
        } else {
            $res['COUNT'] = $this->getEntityCount();
        }
        if ($pageSize) {
            $parameters['limit'] = $pageSize;
        }
        if ($curPage) {
            $parameters['offset'] = ($curPage - 1) * $pageSize;
        }
        $this->getEntityCount();
        $result = $this->entity
            ->getList($parameters)
            ->fetchAll();

        $this->resetEntity();
        $res['ELEMENTS'] = $result;
        return $res;
    }

    /**
     * @param array $filter
     * @return int
     */
    public function getEntityCount($filter = array())
    {

        return $this->getEntity()
            ->getCount($filter);
    }

    /**
     * Get Searchable Fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * @param  array $filter
     * @return int
     */
    public function getCountFind($filter = array())
    {
        $this->applyCriteria();

        $queryBuilder = $this->entity->query();
        $queryBuilder->enableDataDoubling();

        $builderSelect = array(
            new ExpressionField('CNT', 'COUNT(DISTINCT %s)', array('ID'))
        );

        if (empty($filter)) {
            $filter = $this->getParameterByType('filter');
        }

        $maxRecord = $queryBuilder->setSelect($builderSelect)
            ->setFilter($filter)
            ->exec()
            ->fetch();

        $this->resetEntity();

        return $maxRecord['CNT'];
    }

    public function getByXmlId($xmlId)
    {
        $arOrder = ["SORT" => "ASC"];
        $arFilter = [
            'IBLOCK_ID' => $this->getEntity()
                ->getIBlockId(),
            'XML_ID' => $xmlId
        ];
        $arSelectFields = [
            "ID",
            "ACTIVE",
            "NAME"];
        $rsElements = \CIBlockElement::GetList($arOrder, $arFilter, FALSE, FALSE, $arSelectFields);
        if ($arElement = $rsElements->GetNext()) {
            return $arElement;
        }

        return null;
    }

    public function getEnumValues($propId)
    {
        $result = [];
        $data = CIBlockPropertyEnum::GetList([],
            [
                'IBLOCK_ID' => $this->getEntity()
                    ->getIBlockId(),
                'PROPERTY_ID' => $propId
            ]);

        while ($r = $data->GetNext(1, 0)) {
            $result[$r['XML_ID']] = $r;
        }


        return $result;
    }

    public function getProps()
    {
        $result = [];
        $properties = CIBlockProperty::GetList(
            [],
            [
                'IBLOCK_ID' => $this->getEntity()
                    ->getIBlockId()
            ]
        );


        while ($prop_fields = $properties->GetNext(1, 0)) {

            switch ($prop_fields['PROPERTY_TYPE']) {
                case 'L':

                    $prop_fields['VALUES'] = $this->getEnumValues($prop_fields['ID']);
                    break;
            }

            $result[$prop_fields['XML_ID']] = $prop_fields;
        }


        return $result;
    }

    public function save(array $data)
    {
        $prepareData = [];

        $map = $this->getEntity()
            ->getMap();
        $filter = [];
        $this->isNew = false;
        $primaryField = false;


        /** @var ScalarField $field */
        foreach ($map as $field) {
            if ($field->isPrimary()) {
                $primaryField = $field->getName();
                $filter[$field->getName()] = $data[$field->getName()];
            }

            if (!is_null($data[$field->getName()])) {
                $prepareData[$field->getName()] = $data[$field->getName()];
            }
        }

        if (!empty($prepareData) && $primaryField) {
            if ($r = $this->getEntity()
                ->getList(['filter' => $filter])
                ->fetch()) {
                $this->getEntity()
                    ->update($r[$primaryField], $prepareData);
                return $r[$primaryField];

            } else {
                $this->isNew = true;

                $r = $this->getEntity()
                    ->add($prepareData);

                return $r->getId();
            }
        }

    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->isNew;
    }

    public function mapResult(array $items = [], $model = null)
    {
        $props = [];
        $reflector = null;
        if (is_null($model)) {
            $modelName = str_replace('EntityTable', '', get_class($this->getEntity()));
            $reflector = new ReflectionClass($modelName);
            $props = $reflector->getProperties();
        } else {
            $modelName = $model;
            $reflector = new ReflectionClass($modelName);
            $parent = $reflector->getParentClass();
            $props = array_merge($parent->getProperties(), $reflector->getProperties());
        }

        $result = [];

        foreach ($items as $k => $item) {
            $model = $reflector->newInstanceWithoutConstructor(); #new $modelName();
            foreach ($props as $prop) {
                $propertyValue = null;
                $valueName = strtoupper(self::toUnderscore($prop->getName()));

                if (array_key_exists($valueName, $item)) {
                    $propertyValue = $item[$valueName];
                }

                if (array_key_exists($valueName, $item['PROPERTIES'])) {
                    if ($item['PROPERTIES'][$valueName]['PROPERTY_TYPE'] == 'L') {
                        $propertyValue = $item['PROPERTIES'][$valueName]['VALUE_ENUM_ID'];
                    } else {
                        $propertyValue = $item['PROPERTIES'][$valueName]['VALUE'];
                    }

                }

                if (array_key_exists('PROPERTY_' . $valueName . '_ENUM_ID', $item)) {
                    $propertyValue = $item['PROPERTY_' . $valueName . '_ENUM_ID'];
                }

                if ($prop->isPrivate() || $prop->isProtected()) {
                    $prop->setAccessible(true);
                    $prop->setValue($model, $this->typeInference($prop, $propertyValue));
                    $prop->setAccessible(false);

                } else {
                    $prop->setValue($model, $propertyValue);
                }

            }

            $result[] = $model;
        }

        return $result;
    }

    public function typeInference(\ReflectionProperty $prop, $value)
    {
        $factory = DocBlockFactory::createInstance();

        if ($doc = $prop->getDocComment()) {
            $comment = $factory->create($doc);
            foreach ($comment->getTags() as $tag) {
                if ($tag->getName() == 'var') {
                    if ($value || ($comment->hasTag('default') != 'null')) {
                        $type = trim($tag->__toString());
                        $value = new $type($value);
                    }
                }
            }
        }

        return $value;
    }

    public function prepareFields($fields)
    {
        $result = [];
        foreach ($fields as $k => $v) {
            $propCode = self::toUnderscore($k, 1);
            $result[$propCode] = $v;
        }

        return $result;
    }

    public function getModel()
    {
        $reflector = new ReflectionClass($this->getEntity());
        return str_replace('EntityTable', '', $reflector->getName());
    }


}