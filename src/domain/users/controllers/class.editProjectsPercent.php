<?php

namespace leantime\domain\controllers;

use leantime\core;
use leantime\domain\repositories;

class editProjectsPercent
{
    /**
     * run - display template and edit data
     *
     * @access public
     */
    public function run()
    {

        $tpl = new core\template();

        if (core\login::userIsAtLeast("clientManager")) {

            if (isset($_GET['id']) === true) {

                $project = new repositories\projects();
                $userRepo = new repositories\users();
                $language = new core\language();

                $id = (int)($_GET['id']);

                $user = $userRepo->getUser($id);

                if (core\login::userHasRole("clientManager") && $user['clientId'] != core\login::getUserClientId()) {
                    $tpl->display('general.error');
                    exit();
                }


                $tpl->assign('userProjects', $project->getUserProjectsActivityPercent($id));

                if (isset($_POST['save'])) {
                    if (isset($_POST[$_SESSION['formTokenName']]) && $_POST[$_SESSION['formTokenName']] == $_SESSION['formTokenValue']) {
                        $activitiesPercent = $_POST['activityPercent'];
                        $projectsId = $_POST['projectId'];

                        //validate activity percent
                        $sum = 0;
                        try {
                            foreach ($activitiesPercent as $value) {
                                if ($value > 100) {
                                    throw new \Exception("Значение не может быть больше 100");
                                }
                                $sum += $value;
                                if ($sum > 100) {
                                    throw new \Exception("The sum of values cannot exceed 100");
                                }
                            }
                            //end validate

                            for ($i = 0; $i < count($activitiesPercent); $i++) {
                                $project->insertProjectActivity($id, $projectsId[$i], $activitiesPercent[$i]);
                            }

                            $tpl->assign('userProjects', $project->getUserProjectsActivityPercent($id));
                        } catch (\Exception $e) {
                            $tpl->setNotification($language->__("notifications.incorrect_activity_percent"), 'error');
                        }


                    } else {
                        $tpl->setNotification($language->__("notification.form_token_incorrect"), 'error');
                    }
                }

                $tpl->display('users.editProjectsPercent');
            }
        }
    }
}