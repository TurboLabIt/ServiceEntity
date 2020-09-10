<?php
namespace TurboLabIt\ServiceEntityBundle;


abstract class ServiceEntityCollection implements \Iterator, \Countable, \ArrayAccess
{
    const AUTOMATIC_ID  = -1;

    use Foreachable;
    protected $repository;


    abstract public function createService($entity = null);


    public function loadAll()
    {
        return $this->loadByIds();
    }


    public function filterData(array $arrValues, string $field, $keepIfValues): array
    {
        $keepIfValues   = is_array($keepIfValues) ? $keepIfValues : [$keepIfValues];
        $arrFiltered    = [];
        foreach($arrValues as $key => $arrValue) {

            if( array_key_exists($field, $arrValue) && in_array($arrValue[$field], $keepIfValues) ) {

                $arrFiltered[$key] = $arrValue;
            }
        }

        return $arrFiltered;
    }


    public function loadFromData(array $arrValues, $idField = "id")
    {
        $this->arrData = [];

        if( empty($arrValues) ) {

            return $this;
        }

        foreach($arrValues as $arrData) {

            $itemId =  $idField === static::AUTOMATIC_ID
                            ? count($this->arrData) : $arrData[$idField];

            $this->arrData[$itemId] = $this->createService()
                                    ->setData($arrData);
        }

        return $this;
    }


    public function loadFromDataWithEntity(array $arrValues, $idField = "id")
    {
        $this->arrData = [];

        if( empty($arrValues) ) {

            return $this;
        }

        $arrIds = [];
        foreach($arrValues as $arrData) {

            $itemId = $arrData[$idField];
            $arrIds[$itemId] = $arrData[$itemId];
        }

        $this->loadByIds($arrIds);

        foreach($this as $id => $oneService) {

            $key = array_search($id, array_column($arrValues, $idField));
            if( empty($key) && $key !== 0 ) {

                continue;
            }

            $oneService->setData($arrValues[$key]);
        }

        return $this;
    }


    public function loadByIds(array $arrIds = [])
    {
        if( $this->count() ) {

            return $this;
        }

        $arrEntities = $this->repository->findAllById($arrIds);
        foreach($arrEntities as $id => $entity) {

            $this->arrData[$id] = $this->createService($entity);
        }

        return $this;
    }


    public function getAsArray(): array
    {
        $arrData = [];
        foreach($this->arrData as $id => $oneService) {

            $arrData[$id] = $oneService->getAsArray();
        }

        return $arrDatasArray;
    }
}
