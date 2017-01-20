<?php

namespace obo\DataStorage\Tests\Assets\Entities\Contact;

class BusinessProperties extends \obo\DataStorage\Tests\Assets\Entities\ContactProperties {

    /**
     * @obo-autoIncrement
     */
    public $id = 0;

    /**
     * @obo-repositoryName(BusinessContacts)
     * @obo-dataType(string)
     */
    public $companyName = "";

}
