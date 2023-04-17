# Milly.Tools
Reflection and MVC mapping tools for Neos Flow

This package has been implemented to be used in [Milly.CrudForms](https://github.com/Kleisli/Milly.CrudForms) but might also be of use for others.

## ClassMappingService
Determine what Model, Repository and Controller classes belong together. Mapping is done based on the Flow conventions
and can be overruled by defining the constant ENTITY_CLASSNAME in either a Repository or a Controller class.
```
const ENTITY_CLASSNAME = ModelToBeMapped::class;
```
public functions
### getRepositoryClassByModel
@param object|string $model An object (class instance) or a string (class name) of a domain model

@return string

### getControllerClassByModel
@param object|string $model An object (class instance) or a string (class name) of a domain model

@return string

### get*Class functions
* getModelClass
* getRepositoryClass
* getControllerClass

@param string $className a Controller, Model or Repository class name

@return string class

### get*Name functions
* getPackageName
* getControllerName
* getModelName

@param string $className a Controller, Model or Repository class name

@return string just the name of the package, controller or model without the class path

### convertClass
to convert a class of type Model, Repository or Controller into another of these types

@param string $className a Controller, Model or Repository class name

@param string $type one of the ClassMappingService::TYPE_* constants

@return string className of type ClassMappingService::TYPE_*

## ReflectionService
### getTypeOfProperty
@param $className

@param $propertyName

@return string

### getTypeOfRelation
@param $className

@param $relationName

@return string

### isToOneRelation / isToManyRelation
@param $className

@param $relationName

@return bool

### isPropertyPublic
@param object|string $class An object (class instance) or a string (class name)

@param $propertyName

@return bool

### cleanClassName
get the real class name, from a doctrine proxy class

@param $className

@return string
