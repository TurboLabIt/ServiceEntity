<?php
namespace TurboLabIt\ServiceEntityBundle;

use TurboLabIt\ServiceEntityBundle\ServiceEntityNotFoundException;


abstract class ServiceEntity
{
    protected $entity;
    protected $repository;
    protected $arrData = [];
    protected $isSelected = false;


    public function loadEntityById($id): self
    {
        return $this->loadEntity('id', $id);
    }


    public function loadEntity($value, $field): self
    {
        $getMethod = 'get' . ucfirst($field);
        if( empty($this->entity) ||  $this->entity->$getMethod() != $value ) {

            $entity = $this->repository->findOneBy([$field => $value]);
            if (empty($entity) ) {

                $this->throwNotFOundException();
            }

            $this->setEntity($entity);
        }

        return $this;
    }


    public function setEntity($entity): self
    {
        $this->entity = $entity;
        return $this;
    }


    public function getEntity()
    {
        return $this->entity;
    }


    public function setData(array $arrData): self
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


    protected function throwNotFOundException()
    {
        throw new ServiceEntityNotFoundException();
    }


    public function getAsArray(): array
    {
        return array_merge($this->getData(), [

            "id" => $this->entity->getId()
        ]);
    }


    public function __call(string $name, array $arguments)
    {
        if( stripos($name, 'set') === 0 ) {

            $this->entity->$name(...$arguments);
            return $this;
        }


        $fromData = $this->getData($name);
        if( $fromData !== null ) {

            return $fromData;
        }


        if( stripos($name, 'get') !== 0 ) {

            $name = 'get' . ucfirst($name);
        }

        return $this->entity->$name(...$arguments);
    }


    public function setSelected(): self
    {
        $this->isSelected = true;
        return $this;
    }


    public function isSelected(): bool
    {
        return $this->isSelected;
    }
}
