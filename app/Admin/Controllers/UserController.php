<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('email_verified_at', __('Email verified at'));
        $grid->column('password', __('Password'));
        $grid->column('remember_token', __('Remember token'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('is_active', __('Is active'));
        $grid->column('type', __('Type'));
        $grid->column('provider_id', __('Provider id'));
        $grid->column('stripe_id', __('Stripe id'));
        $grid->column('pm_type', __('Pm type'));
        $grid->column('pm_last_four', __('Pm last four'));
        $grid->column('trial_ends_at', __('Trial ends at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('password', __('Password'));
        $show->field('remember_token', __('Remember token'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('is_active', __('Is active'));
        $show->field('type', __('Type'));
        $show->field('provider_id', __('Provider id'));
        $show->field('stripe_id', __('Stripe id'));
        $show->field('pm_type', __('Pm type'));
        $show->field('pm_last_four', __('Pm last four'));
        $show->field('trial_ends_at', __('Trial ends at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $form->text('name', __('Name'));
        $form->email('email', __('Email'));
        $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
        $form->password('password', __('Password'));
        $form->text('remember_token', __('Remember token'));
        $form->switch('is_active', __('Is active'))->default(1);
        $form->text('type', __('Type'))->default('1');
        $form->text('provider_id', __('Provider id'));
        $form->text('stripe_id', __('Stripe id'));
        $form->text('pm_type', __('Pm type'));
        $form->text('pm_last_four', __('Pm last four'));
        $form->datetime('trial_ends_at', __('Trial ends at'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
