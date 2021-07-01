<?php
namespace TurboLabIt\ServiceEntityBundle;


abstract class ServiceEntity
{
    protected $entity;
    protected $repository;
    protected $arrData = [];
    protected $isSelected = false;


    public function loadEntityById($id)
    {
        return $this->loadEntity(['id' => $id]);
    }


    public function loadEntity($arrFieldsValues)
    {
        $getMethod = 'get' . ucfirst($field);
        if( empty($this->entity) ||  $this->entity->$getMethod() != $value ) {

            $entity = $this->repository->findOneBy($arrFieldsValues);
            if (empty($entity) ) {

                $this->throwNotFoundException();
            }

            $this->setEntity($entity);
        }

        return $this;
    }


    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }


    public function getEntity()
    {
        return $this->entity;
    }


    public function setData(array $arrData)
    {
        $this->arrData = $arrData;
        return $this;
    }


    public function getData($index = null)
    {
        if( $index === null ) {

            return $this->arrData;
        }

        if( array_key_exists($index, $this->arrData) ) {

            return $this->arrData[$index];
        }

        return null;
    }


    protected function throwNotFoundException()
    {
        throw new ServiceEntityNotFoundException();
    }


    public function setSelected()
    {
        $this->isSelected = true;
        return $this;
    }


    public function isSelected(): bool
    {
        return $this->isSelected;
    }


    public function getAsArray(array $options = []): array
    {
        return array_merge($this->getData(), [

            "id" => $this->entity->getId()
        ]);
    }


    public function __call(string $name, array $arguments)
    {
        $fromData = $this->getData($name);
        if( $fromData !== null ) {

            return $fromData;
        }
        
        
        if( stripos($name, 'set') === 0 ) {

            $this->entity->$name(...$arguments);
            return $this;
        }


        if( !method_exists($this->entity, $name) && stripos($name, 'get') !== 0 ) {

            $name = 'get' . ucfirst($name);
        }

        return $this->entity->$name(...$arguments);
    }
}
