<?php
// employee-view.php
// Copyright © 2019 NextStep IT Training. All rights reserved.
//

require($_SERVER['DOCUMENT_ROOT'] . '/_controllers/employee-controller.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/authorization.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/_include/utility.php');
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

                <input type="hidden" name="employee_id" value="<?= $employee_id ?>" />
                <input type="hidden" name="version" value="<?= $version ?>" />

                <div class="error-message">
                    <?php renderErrorMessages($errors['general'], '<br />') ?>
                </div>

                <table class="form">

                    <thead>
                    </thead>

                    <tbody>

                        <tr>
                            <td class="form-label"></td>
                            <td class="form-field">
                                <div class="photo">
                                    <img class="photo" src="/services/photo.php?employee_id=<?= $employee_id ?>" alt="Employee Photograph" /><br />
                                    <input type="file" class="photo" name="photo" id="photo" />
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="hire_date">Hire Date:</label></td>
                            <td class="form-field"><input id="hire_date" name="hire_date" type="text" value="<?= $hire_date ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php renderErrorMessages($errors['hire_date']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <?php $employee_no = ($role === 'administrator') ? $employee_no : '***' . substr($employee_no, -4) ?>
                            <td class="form-label"><label for="employee_no">Employee Number:</label></td>
                            <td class="form-field"><input id="employee_no" name="employee_no" type="text" value="<?= $employee_no ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php renderErrorMessages($errors['employee_no']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <?php $government_no = ($role === 'administrator') ? $government_no : '***' . substr($government_no, -4) ?>
                            <td class="form-label"><label for="government_no">Government Number:</label></td>
                            <td class="form-field"><input id="government_no" name="government_no" type="text" value="<?= $government_no ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php renderErrorMessages($errors['government_no']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="first_name">First Name:</label></td>
                            <td class="form-field"><input id="first_name" name="first_name" type="text" value="<?= $first_name ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php renderErrorMessages($errors['first_name']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="last_name">Last Name:</label></td>
                            <td class="form-field"><input id="last_name" name="last_name" type="text" value="<?= $last_name ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php renderErrorMessages($errors['last_name']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="email">Email:</label></td>
                            <td class="form-field"><input id="email" name="email" type="text" value="<?= $email ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php renderErrorMessages($errors['email']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="street">Street:</label></td>
                            <td class="form-field"><input id="street" name="street" type="text" value="<?= $street ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php renderErrorMessages($errors['street']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="city">City:</label></td>
                            <td class="form-field"><input id="city" name="city" type="text" value="<?= $city ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php renderErrorMessages($errors['city']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="state_province">State or Province:</label></td>
                            <td class="form-field"><input id="state_province" name="state_province" type="text" value="<?= $state_province ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php renderErrorMessages($errors['state_province']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="postal_code">Postal Code:</label></td>
                            <td class="form-field"><input id="postal_code" name="postal_code" type="text" value="<?= $postal_code ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php renderErrorMessages($errors['postal_code']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="country_code">Country Code:</label></td>
                            <td class="form-field"><input id="country_code" name="country_code" type="text" value="<?= $country_code ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php renderErrorMessages($errors['country_code']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="salary_employee">Salary Employee:</label></td>
                            <td class="form-field"><input id="salary_employee" name="salary_employee" type="checkbox"<?= $salary_employee ? ' checked="checked"' : '' ?>/></td>
                            <td class="form-error"></td>
                        </tr>

                        <tr>
                            <td class="form-label"><label for="rate">Pay Rate:</label></td>
                            <td class="form-field"><input id="rate" name="rate" type="text" value="<?= $rate ?>" /></td>
                            <td class="form-error">
                                <div class="error-message">
                                    <?php renderErrorMessages($errors['rate']) ?>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="form-label"></td>
                            <td class="form-field">
                                <a href="employees.php"><input type="button" value="Cancel" /></a>
                                <?php if ($role === 'administrator') { ?>
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
