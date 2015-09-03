<?php
namespace Mailer\Controller;

use SbxCommon\Crud\AbstractController as AbstractCrudController;
/**
 * Class AclRolesController
 * @package Mailer\Controller
 *
 *  * @method \Mailer\Model\NotificationModel getModelToProcess
 */
class NotificationsController extends AbstractCrudController
{
    /**
     * @return \Mailer\Model\NotificationModel
     */
    public function getModel()
    {
        return $this->getServiceLocator()->get('Mailer\Model\Notification');
    }

    public function moxiemanagerAction()
    {
        $mm = $this->getServiceLocator()->get('Moxiemanager/Bridge')->getPreset('mailer')->process();
        exit();
    }
}
