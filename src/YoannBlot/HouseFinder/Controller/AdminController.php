<?php

namespace YoannBlot\HouseFinder\Controller;

use YoannBlot\Framework\Controller\AbstractController;
use YoannBlot\Framework\Controller\Exception\Redirect404Exception;
use YoannBlot\HouseFinder\Model\Repository\CityRepository;

/**
 * Class AdminController.
 *
 * @package YoannBlot\HouseFinder\Controller
 *
 * @path("/admin")
 */
class AdminController extends AbstractController {

    /**
     * @inheritdoc
     */
    public function autoSelectPage () {
        $this->setCurrentPage('city');
    }

    /**
     * @return CityRepository current repository.
     */
    private function getRepository (): CityRepository {
        return new CityRepository();
    }

    /**
     * @return array
     *
     * @throws Redirect404Exception
     */
    protected function cityPage () : array {
        return [
            'cities' => $this->getRepository()->getAll()
        ];
    }

}