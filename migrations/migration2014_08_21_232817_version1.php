<?php
namespace Addon\Stripe\Migrations;

use \App\Libraries\BaseMigration;

class Migration2014_08_21_232817_version1 extends BaseMigration
{
    /**
     * migration 'up' function to install items
     *
     * @param   int     addon_id
     */
    public function up($addon_id)
    {
        $Gateway = new \Gateway();
        $Gateway->slug = 'stripe';
        $Gateway->name = 'Stripe';
        $Gateway->addon_id = $addon_id;
        $Gateway->is_merchant = 0;
        $Gateway->process_cc = 0;
        $Gateway->process_ach = 0;
        $Gateway->is_active = 1;
        $Gateway->sort = 10;
        $Gateway->save();

        // Create the settings category
        $category = new \SettingCategory();
        $category->slug = 'stripe';
        $category->title = 'stripe_settings';
        $category->is_visible = '1';
        $category->sort = '99';
        $category->addon_id = $addon_id;
        $category->save();

        \Setting::insert(
            array(
                array(
                    'slug' => 'stripe_secret_key',
                    'title' => 'stripe_secret_key',
                    'field_type' => 'text',
                    'setting_category_id' => $category->id,
                    'editable' => 1,
                    'required' => 1,
                    'addon_id' => $addon_id,
                    'sort' => 10,
                    'value' => '',
                    'default_value' => '',
                    'created_at' => $this->date,
                    'updated_at' => $this->date
                ),
                array(
                    'slug' => 'stripe_publishable_key',
                    'title' => 'stripe_publishable_key',
                    'field_type' => 'text',
                    'setting_category_id' => $category->id,
                    'editable' => 1,
                    'required' => 1,
                    'addon_id' => $addon_id,
                    'sort' => 20,
                    'value' => '',
                    'default_value' => '',
                    'created_at' => $this->date,
                    'updated_at' => $this->date
                ),
                array(
                    'slug' => 'stripe_description',
                    'title' => 'stripe_description',
                    'field_type' => 'text',
                    'setting_category_id' => $category->id,
                    'editable' => 1,
                    'required' => 1,
                    'addon_id' => $addon_id,
                    'sort' => 20,
                    'value' => '',
                    'default_value' => '',
                    'created_at' => $this->date,
                    'updated_at' => $this->date
                ),
                array(
                    'slug' => 'stripe_image',
                    'title' => 'stripe_image',
                    'field_type' => 'text',
                    'setting_category_id' => $category->id,
                    'editable' => 1,
                    'required' => 1,
                    'addon_id' => $addon_id,
                    'sort' => 20,
                    'value' => '',
                    'default_value' => '',
                    'created_at' => $this->date,
                    'updated_at' => $this->date
                )
            )
        );
    }

    /**
     * migration 'down' function to delete items
     *
     * @param   int     addon_id
     */
    public function down($addon_id)
    {
        // Remove gateway record
        $Gateway = \Gateway::where('addon_id', '=', $addon_id)
            ->where('slug', '=', 'stripe')
            ->first();

        \GatewayCurrency::where('gateway_id', '=', $Gateway->id)
            ->delete();

        $Gateway->delete();

        // Remove all settings
        \Setting::where('addon_id', '=', $addon_id)->delete();

        // Remove all settings groups
        \SettingCategory::where('addon_id', '=', $addon_id)->delete();
    }

}