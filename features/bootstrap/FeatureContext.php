<?php

class FeatureContext extends Behat\Behat\Context\BehatContext {

    public function __construct(array $parameters) {
        $this->useContext('nodes', new TreeContext());
    }

}
