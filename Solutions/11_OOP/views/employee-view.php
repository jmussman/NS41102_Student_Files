<?php
// employee-view.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

require($_SERVER['DOCUMENT_ROOT'] . '/_controllers/EmployeeController.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/Authorization.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/Utility.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Employee</title>
    <link rel="stylesheet" type="text/css" href="/assets/styles/application.css" />
</head>

<body>
    <?php require($_SERVER['DOCUMENT_ROOT'] . '/_include/page-header.php') ?>

    <div id="main" class="y-scroll">

        <div id="content">

            <form method="POST" enctype="multipart/form-data">

                <input type="hidden" name="employee_id" value="<?= $controller->employee_id ?>" />
                <input type="hidden" name="version" value="<?= $controller->version ?>" />

                <div class="error-message">
                    <?php Utility::renderErrorMessages($controller->errors['general'], '<br />') ?>
                </div>

                <table class="form">

                    <thead>
                    </thead>

                    <tbody>

                        <tr>
                            <td class="form-label"></td>
                            <td class="form-field">
                                <div class="photo">
                                    <img class="photo" src="/services/photo.php?employee_id=<?= $controller->employee_id ?>" alt="Employee Photograph" /><br />
                                    <input type="file" class="photo" name="photo" id="photo" />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="hire_date">Hire Date:</label></td>
                            <td class="form-field"><input id="hire_date" name="hire_date" type="text" value="<?= $controller->hire_date ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php Utility::renderErrorMessages($controller->errors['hire_date']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <?php $employee_no = ($authorization->role === 'administrator') ? $controller->employee_no : '***' . substr($controller->employee_no, -4) ?>
                            <td class="form-label"><label for="employee_no">Employee Number:</label></td>
                            <td class="form-field"><input id="employee_no" name="employee_no" type="text" value="<?= $employee_no ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php Utility::renderErrorMessages($controller->errors['employee_no']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <?php $government_no = ($authorization->role === 'administrator') ? $controller->government_no : '***' . substr($controller->government_no, -4) ?>
                            <td class="form-label"><label for="government_no">Government Number:</label></td>
                            <td class="form-field"><input id="government_no" name="government_no" type="text" value="<?= $government_no ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php Utility::renderErrorMessages($controller->errors['government_no']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="first_name">First Name:</label></td>
                            <td class="form-field"><input id="first_name" name="first_name" type="text" value="<?= $controller->first_name ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php Utility::renderErrorMessages($controller->errors['first_name']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="last_name">Last Name:</label></td>
                            <td class="form-field"><input id="last_name" name="last_name" type="text" value="<?= $controller->last_name ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php Utility::renderErrorMessages($controller->errors['last_name']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="email">Email:</label></td>
                            <td class="form-field"><input id="email" name="email" type="text" value="<?= $controller->email ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php Utility::renderErrorMessages($controller->errors['email']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="street">Street:</label></td>
                            <td class="form-field"><input id="street" name="street" type="text" value="<?= $controller->street ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php Utility::renderErrorMessages($controller->errors['street']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="city">City:</label></td>
                            <td class="form-field"><input id="city" name="city" type="text" value="<?= $controller->city ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php Utility::renderErrorMessages($controller->errors['city']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="state_province">State or Province:</label></td>
                            <td class="form-field"><input id="state_province" name="state_province" type="text" value="<?= $controller->state_province ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php Utility::renderErrorMessages($controller->errors['state_province']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="postal_code">Postal Code:</label></td>
                            <td class="form-field"><input id="postal_code" name="postal_code" type="text" value="<?= $controller->postal_code ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php Utility::renderErrorMessages($controller->errors['postal_code']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="country_code">Country Code:</label></td>
                            <td class="form-field"><input id="country_code" name="country_code" type="text" value="<?= $controller->country_code ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php Utility::renderErrorMessages($controller->errors['country_code']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="salary_employee">Salary Employee:</label></td>
                            <td class="form-field"><input id="salary_employee" name="salary_employee" type="checkbox"<?= $controller->salary_employee ? ' checked="checked"' : '' ?>/></td>
                            <td class="form-error"></td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="rate">Pay Rate:</label></td>
                            <td class="form-field"><input id="rate" name="rate" type="text" value="<?= $controller->rate ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php Utility::renderErrorMessages($controller->errors['rate']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"></td>
                            <td class="form-field">
                                <a href="employees.php"><input type="button" value="Cancel" /></a>
                                <?php if ($authorization->role === 'administrator') { ?>
                                    <input type="submit" />
                                <?php } ?>
                            </td>
                            <td class="form-error"></td>
                        </tr>

                    </tbody>

                </table>
            </form>

        </div>
    </div>

    <?php require($_SERVER['DOCUMENT_ROOT'] . '/_include/page-footer.php') ?>

</body>
</html>
