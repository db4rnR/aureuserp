CREATE TABLE "database.sqlite"."migrations" ("id" INTEGER,"migration" varchar,"batch" INTEGER);

CREATE TABLE "database.sqlite"."sqlite_sequence" ("name" ,"seq" );

CREATE TABLE "database.sqlite"."password_reset_tokens" ("email" varchar,"token" varchar,"created_at" datetime);

CREATE TABLE "database.sqlite"."sessions" ("id" varchar,"user_id" INTEGER,"ip_address" varchar,"user_agent" TEXT,"payload" TEXT,"last_activity" INTEGER);

CREATE TABLE "database.sqlite"."cache" ("key" varchar,"value" TEXT,"expiration" INTEGER);

CREATE TABLE "database.sqlite"."cache_locks" ("key" varchar,"owner" varchar,"expiration" INTEGER);

CREATE TABLE "database.sqlite"."jobs" ("id" INTEGER,"queue" varchar,"payload" TEXT,"attempts" INTEGER,"reserved_at" INTEGER,"available_at" INTEGER,"created_at" INTEGER);

CREATE TABLE "database.sqlite"."job_batches" ("id" varchar,"name" varchar,"total_jobs" INTEGER,"pending_jobs" INTEGER,"failed_jobs" INTEGER,"failed_job_ids" TEXT,"options" TEXT,"cancelled_at" INTEGER,"created_at" INTEGER,"finished_at" INTEGER);

CREATE TABLE "database.sqlite"."failed_jobs" ("id" INTEGER,"uuid" varchar,"connection" TEXT,"queue" TEXT,"payload" TEXT,"exception" TEXT,"failed_at" datetime);

CREATE TABLE "database.sqlite"."settings" ("id" INTEGER,"group" varchar,"name" varchar,"locked" tinyint(1),"payload" TEXT,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."permissions" ("id" INTEGER,"name" varchar,"guard_name" varchar,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."model_has_permissions" ("permission_id" INTEGER,"model_type" varchar,"model_id" INTEGER);

CREATE TABLE "database.sqlite"."role_has_permissions" ("permission_id" INTEGER,"role_id" INTEGER);

CREATE TABLE "database.sqlite"."roles" ("id" INTEGER,"name" varchar,"guard_name" varchar,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."model_has_roles" ("role_id" INTEGER,"model_type" varchar,"model_id" INTEGER);

CREATE TABLE "database.sqlite"."plugins" ("id" INTEGER,"name" varchar,"author" varchar,"summary" TEXT,"description" TEXT,"latest_version" varchar,"license" varchar,"is_active" tinyint(1),"is_installed" tinyint(1),"sort" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."plugin_dependencies" ("plugin_id" INTEGER,"dependency_id" INTEGER);

CREATE TABLE "database.sqlite"."user_invitations" ("id" INTEGER,"email" varchar,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."teams" ("id" INTEGER,"name" varchar,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."user_team" ("user_id" INTEGER,"team_id" INTEGER);

CREATE TABLE "database.sqlite"."users" ("id" INTEGER,"name" varchar,"email" varchar,"email_verified_at" datetime,"language" varchar,"is_active" tinyint(1),"password" varchar,"remember_token" varchar,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime,"resource_permission" varchar,"default_company_id" INTEGER,"partner_id" INTEGER);

CREATE TABLE "database.sqlite"."table_views" ("id" INTEGER,"name" varchar,"icon" varchar,"color" varchar,"is_public" tinyint(1),"filters" TEXT,"filterable_type" varchar,"user_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."table_view_favorites" ("id" INTEGER,"is_favorite" tinyint(1),"view_type" varchar,"view_key" varchar,"filterable_type" varchar,"user_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."user_allowed_companies" ("id" INTEGER,"user_id" INTEGER,"company_id" INTEGER);

CREATE TABLE "database.sqlite"."companies" ("id" INTEGER,"parent_id" INTEGER,"currency_id" INTEGER,"creator_id" INTEGER,"sort" INTEGER,"name" varchar,"company_id" varchar,"tax_id" varchar,"registration_number" varchar,"email" varchar,"phone" varchar,"mobile" varchar,"website" varchar,"color" varchar,"is_active" tinyint(1),"founded_date" date,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime,"partner_id" INTEGER,"street1" varchar,"street2" varchar,"city" varchar,"zip" varchar,"state_id" INTEGER,"country_id" INTEGER);

CREATE TABLE "database.sqlite"."chatter_messages" ("id" INTEGER,"company_id" INTEGER,"activity_type_id" INTEGER,"assigned_to" INTEGER,"messageable_type" varchar,"messageable_id" INTEGER,"type" varchar,"name" varchar,"subject" varchar,"body" TEXT,"summary" TEXT,"is_internal" tinyint(1),"date_deadline" date,"pinned_at" date,"log_name" varchar,"causer_type" varchar,"causer_id" INTEGER,"event" varchar,"properties" TEXT,"created_at" datetime,"updated_at" datetime,"is_read" tinyint(1));

CREATE TABLE "database.sqlite"."activity_types" ("id" INTEGER,"sort" INTEGER,"delay_count" INTEGER,"delay_unit" varchar,"delay_from" varchar,"icon" varchar,"decoration_type" varchar,"chaining_type" varchar,"plugin" varchar,"category" varchar,"name" varchar,"summary" TEXT,"default_note" TEXT,"is_active" tinyint(1),"keep_done" tinyint(1),"creator_id" INTEGER,"default_user_id" INTEGER,"activity_plan_id" INTEGER,"triggered_next_type_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."activity_plans" ("id" INTEGER,"plugin" varchar,"name" varchar,"is_active" tinyint(1),"creator_id" INTEGER,"company_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime,"department_id" INTEGER);

CREATE TABLE "database.sqlite"."activity_plan_templates" ("id" INTEGER,"sort" INTEGER,"plan_id" INTEGER,"activity_type_id" INTEGER,"responsible_id" INTEGER,"creator_id" INTEGER,"delay_count" INTEGER,"delay_unit" varchar,"delay_from" varchar,"summary" TEXT,"responsible_type" varchar,"note" TEXT,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."employees_departments" ("id" INTEGER,"company_id" INTEGER,"parent_id" INTEGER,"master_department_id" INTEGER,"creator_id" INTEGER,"name" varchar,"complete_name" varchar,"parent_path" varchar,"color" TEXT,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime,"manager_id" INTEGER);

CREATE TABLE "database.sqlite"."employees_employees" ("id" INTEGER,"time_zone" varchar,"work_permit" varchar,"address_id" INTEGER,"leave_manager_id" INTEGER,"bank_account_id" INTEGER,"private_state_id" INTEGER,"private_country_id" INTEGER,"company_id" INTEGER,"user_id" INTEGER,"creator_id" INTEGER,"calendar_id" INTEGER,"department_id" INTEGER,"job_id" INTEGER,"partner_id" INTEGER,"work_location_id" INTEGER,"parent_id" INTEGER,"coach_id" INTEGER,"country_id" INTEGER,"state_id" INTEGER,"country_of_birth" INTEGER,"departure_reason_id" INTEGER,"attendance_manager_id" INTEGER,"name" varchar,"job_title" varchar,"work_phone" varchar,"mobile_phone" varchar,"color" varchar,"children" INTEGER,"distance_home_work" INTEGER,"km_home_work" INTEGER,"distance_home_work_unit" varchar,"work_email" varchar,"private_phone" varchar,"private_email" varchar,"private_street1" varchar,"private_street2" varchar,"private_city" varchar,"private_zip" varchar,"private_car_plate" varchar,"lang" varchar,"gender" varchar,"birthday" varchar,"marital" varchar,"spouse_complete_name" varchar,"spouse_birthdate" varchar,"place_of_birth" varchar,"ssnid" varchar,"sinid" varchar,"identification_id" varchar,"passport_id" varchar,"permit_no" varchar,"visa_no" varchar,"certificate" varchar,"study_field" varchar,"study_school" varchar,"emergency_contact" varchar,"emergency_phone" varchar,"employee_type" varchar,"barcode" varchar,"pin" varchar,"visa_expire" varchar,"work_permit_expiration_date" varchar,"departure_date" varchar,"departure_description" TEXT,"additional_note" TEXT,"notes" TEXT,"is_active" tinyint(1),"is_flexible" tinyint(1),"is_fully_flexible" tinyint(1),"work_permit_scheduled_activity" tinyint(1),"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."countries" ("id" INTEGER,"currency_id" INTEGER,"phone_code" varchar,"code" varchar,"name" varchar,"state_required" tinyint(1),"zip_required" tinyint(1),"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."currencies" ("id" INTEGER,"name" varchar,"symbol" varchar,"iso_numeric" INTEGER,"decimal_places" INTEGER,"full_name" varchar,"rounding" numeric,"active" tinyint(1),"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."products_price_rules" ("id" INTEGER,"name" varchar,"sort" INTEGER,"currency_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."products_price_rule_items" ("id" INTEGER,"apply_to" varchar,"display_apply_to" varchar,"base" varchar,"type" varchar,"min_quantity" numeric,"fixed_price" numeric,"price_discount" numeric,"price_round" numeric,"price_surcharge" numeric,"price_markup" numeric,"price_min_margin" numeric,"percent_price" numeric,"starts_at" datetime,"ends_at" datetime,"price_rule_id" INTEGER,"base_price_rule_id" INTEGER,"product_id" INTEGER,"category_id" INTEGER,"currency_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."products_products" ("id" INTEGER,"type" varchar,"name" varchar,"service_tracking" varchar,"reference" varchar,"barcode" varchar,"price" numeric,"cost" numeric,"volume" numeric,"weight" numeric,"description" TEXT,"description_purchase" TEXT,"description_sale" TEXT,"enable_sales" tinyint(1),"enable_purchase" tinyint(1),"is_favorite" tinyint(1),"is_configurable" tinyint(1),"sort" INTEGER,"images" TEXT,"parent_id" INTEGER,"uom_id" INTEGER,"uom_po_id" INTEGER,"category_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime,"sale_delay" INTEGER,"tracking" varchar,"description_picking" TEXT,"description_pickingout" TEXT,"description_pickingin" TEXT,"is_storable" tinyint(1),"expiration_time" INTEGER,"use_time" INTEGER,"removal_time" INTEGER,"alert_time" INTEGER,"use_expiration_date" tinyint(1),"responsible_id" INTEGER,"property_account_income_id" INTEGER,"property_account_expense_id" INTEGER,"image" varchar,"service_type" varchar,"sale_line_warn" varchar,"expense_policy" TEXT,"invoice_policy" TEXT,"sales_ok" tinyint(1),"purchase_ok" tinyint(1),"sale_line_warn_msg" varchar);

CREATE TABLE "database.sqlite"."products_product_tag" ("tag_id" INTEGER,"product_id" INTEGER);

CREATE TABLE "database.sqlite"."products_tags" ("id" INTEGER,"name" varchar,"color" varchar,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."products_product_attributes" ("id" INTEGER,"sort" INTEGER,"product_id" INTEGER,"attribute_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."products_attributes" ("id" INTEGER,"name" varchar,"type" varchar,"sort" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."products_attribute_options" ("id" INTEGER,"name" varchar,"color" varchar,"extra_price" numeric,"sort" INTEGER,"attribute_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."products_product_attribute_values" ("id" INTEGER,"extra_price" numeric,"product_id" INTEGER,"attribute_id" INTEGER,"product_attribute_id" INTEGER,"attribute_option_id" INTEGER);

CREATE TABLE "database.sqlite"."products_product_combinations" ("id" INTEGER,"product_id" INTEGER,"product_attribute_value_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."products_product_suppliers" ("id" INTEGER,"sort" INTEGER,"delay" INTEGER,"product_name" varchar,"product_code" varchar,"starts_at" date,"ends_at" date,"min_qty" numeric,"price" numeric,"discount" numeric,"product_id" INTEGER,"partner_id" INTEGER,"currency_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."partners_partners" ("id" INTEGER,"account_type" varchar,"sub_type" varchar,"name" varchar,"avatar" varchar,"email" varchar,"job_title" varchar,"website" varchar,"tax_id" varchar,"phone" varchar,"mobile" varchar,"color" varchar,"company_registry" varchar,"reference" varchar,"parent_id" INTEGER,"creator_id" INTEGER,"user_id" INTEGER,"title_id" INTEGER,"company_id" INTEGER,"industry_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime,"street1" varchar,"street2" varchar,"city" varchar,"zip" varchar,"state_id" INTEGER,"country_id" INTEGER,"email_verified_at" datetime,"is_active" tinyint(1),"password" varchar,"remember_token" varchar,"message_bounce" INTEGER,"supplier_rank" INTEGER,"customer_rank" INTEGER,"invoice_warning" varchar,"autopost_bills" varchar,"credit_limit" varchar,"ignore_abnormal_invoice_date" varchar,"ignore_abnormal_invoice_amount" varchar,"invoice_sending_method" varchar,"invoice_edi_format_store" varchar,"trust" INTEGER,"invoice_warn_msg" INTEGER,"debit_limit" numeric,"peppol_endpoint" varchar,"peppol_eas" varchar,"sale_warn" varchar,"sale_warn_msg" varchar,"comment" TEXT,"property_account_payable_id" INTEGER,"property_account_receivable_id" INTEGER,"property_account_position_id" INTEGER,"property_payment_term_id" INTEGER,"property_supplier_payment_term_id" INTEGER,"property_inbound_payment_method_line_id" INTEGER,"property_outbound_payment_method_line_id" INTEGER);

CREATE TABLE "database.sqlite"."chatter_followers" ("id" INTEGER,"followable_type" varchar,"followable_id" INTEGER,"partner_id" INTEGER,"followed_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."partners_bank_accounts" ("id" INTEGER,"account_number" varchar,"account_holder_name" varchar,"is_active" tinyint(1),"can_send_money" tinyint(1),"creator_id" INTEGER,"partner_id" INTEGER,"bank_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."banks" ("id" INTEGER,"name" varchar,"code" varchar,"email" varchar,"phone" varchar,"street1" varchar,"street2" varchar,"city" varchar,"zip" varchar,"state_id" INTEGER,"country_id" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."states" ("id" INTEGER,"country_id" INTEGER,"name" varchar,"code" varchar,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_journals" ("id" INTEGER,"default_account_id" INTEGER,"suspense_account_id" INTEGER,"sort" INTEGER,"currency_id" INTEGER,"company_id" INTEGER,"profit_account_id" INTEGER,"loss_account_id" INTEGER,"bank_account_id" INTEGER,"creator_id" INTEGER,"color" varchar,"access_token" varchar,"code" varchar,"type" varchar,"invoice_reference_type" varchar,"invoice_reference_model" varchar,"bank_statements_source" varchar,"name" varchar,"order_override_regex" TEXT,"auto_check_on_post" tinyint(1),"restrict_mode_hash_table" tinyint(1),"refund_order" tinyint(1),"payment_order" tinyint(1),"show_on_dashboard" tinyint(1),"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_accounts" ("id" INTEGER,"currency_id" INTEGER,"creator_id" INTEGER,"account_type" varchar,"name" varchar,"code" varchar,"note" varchar,"deprecated" tinyint(1),"reconcile" tinyint(1),"non_trade" tinyint(1),"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_taxes" ("id" INTEGER,"sort" INTEGER,"company_id" INTEGER,"tax_group_id" INTEGER,"cash_basis_transition_account_id" INTEGER,"country_id" INTEGER,"creator_id" INTEGER,"type_tax_use" varchar,"tax_scope" varchar,"formula" varchar,"amount_type" varchar,"price_include_override" varchar,"tax_exigibility" varchar,"name" varchar,"description" varchar,"invoice_label" varchar,"invoice_legal_notes" TEXT,"amount" numeric,"is_active" tinyint(1),"include_base_amount" tinyint(1),"is_base_affected" tinyint(1),"analytic" tinyint(1),"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_tax_groups" ("id" INTEGER,"sort" INTEGER,"company_id" INTEGER,"country_id" INTEGER,"creator_id" INTEGER,"name" varchar,"preceding_subtotal" varchar,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_account_move_lines" ("id" INTEGER,"sort" INTEGER,"move_id" INTEGER,"journal_id" INTEGER,"company_id" INTEGER,"company_currency_id" INTEGER,"reconcile_id" INTEGER,"payment_id" INTEGER,"tax_repartition_line_id" INTEGER,"account_id" INTEGER,"currency_id" INTEGER,"partner_id" INTEGER,"group_tax_id" INTEGER,"tax_line_id" INTEGER,"tax_group_id" INTEGER,"statement_id" INTEGER,"statement_line_id" INTEGER,"product_id" INTEGER,"uom_id" INTEGER,"created_by" INTEGER,"move_name" varchar,"parent_state" varchar,"reference" varchar,"name" varchar,"matching_number" varchar,"display_type" varchar,"date" date,"invoice_date" date,"date_maturity" date,"discount_date" date,"analytic_distribution" TEXT,"debit" numeric,"credit" numeric,"balance" numeric,"amount_currency" numeric,"tax_base_amount" numeric,"amount_residual" numeric,"amount_residual_currency" numeric,"quantity" numeric,"price_unit" numeric,"price_subtotal" numeric,"price_total" numeric,"discount" numeric,"discount_amount_currency" numeric,"discount_balance" numeric,"is_imported" tinyint(1),"tax_tag_invert" tinyint(1),"reconciled" tinyint(1),"is_downpayment" tinyint(1),"created_at" datetime,"updated_at" datetime,"full_reconcile_id" INTEGER,"purchase_order_line_id" INTEGER);

CREATE TABLE "database.sqlite"."accounts_account_payment_register_move_lines" ("payment_register_id" INTEGER,"move_line_id" INTEGER);

CREATE TABLE "database.sqlite"."accounts_payment_registers" ("id" INTEGER,"currency_id" INTEGER,"journal_id" INTEGER,"partner_bank_id" INTEGER,"custom_user_currency_id" INTEGER,"source_currency_id" INTEGER,"company_id" INTEGER,"partner_id" INTEGER,"payment_method_line_id" INTEGER,"writeoff_account_id" INTEGER,"creator_id" INTEGER,"communication" varchar,"installments_mode" varchar,"payment_type" varchar,"partner_type" varchar,"payment_difference_handling" varchar,"writeoff_label" varchar,"payment_date" date,"amount" numeric,"custom_user_amount" numeric,"source_amount" numeric,"source_amount_currency" numeric,"group_payment" tinyint(1),"can_group_payments" tinyint(1),"payment_token_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_payment_method_lines" ("id" INTEGER,"sort" INTEGER,"payment_method_id" INTEGER,"payment_account_id" INTEGER,"journal_id" INTEGER,"creator_id" INTEGER,"name" varchar,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_payment_methods" ("id" INTEGER,"code" varchar,"payment_type" varchar,"name" varchar,"created_by" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_account_payments" ("id" INTEGER,"journal_id" INTEGER,"company_id" INTEGER,"partner_bank_id" INTEGER,"paired_internal_transfer_payment_id" INTEGER,"payment_method_line_id" INTEGER,"payment_method_id" INTEGER,"currency_id" INTEGER,"partner_id" INTEGER,"outstanding_account_id" INTEGER,"destination_account_id" INTEGER,"created_by" INTEGER,"name" varchar,"state" varchar,"payment_type" varchar,"partner_type" varchar,"memo" varchar,"payment_reference" varchar,"date" date,"amount" numeric,"amount_company_currency_signed" numeric,"is_reconciled" tinyint(1),"is_matched" tinyint(1),"is_sent" tinyint(1),"source_payment_id" INTEGER,"created_at" datetime,"updated_at" datetime,"move_id" INTEGER,"payment_token_id" INTEGER,"payment_transaction_id" INTEGER);

CREATE TABLE "database.sqlite"."accounts_accounts_move_payment" ("invoice_id" INTEGER,"payment_id" INTEGER);

CREATE TABLE "database.sqlite"."accounts_account_moves" ("id" INTEGER,"sort" INTEGER,"journal_id" INTEGER,"company_id" INTEGER,"tax_cash_basis_origin_move_id" INTEGER,"auto_post_origin_id" INTEGER,"origin_payment_id" INTEGER,"secure_sequence_number" INTEGER,"invoice_payment_term_id" INTEGER,"partner_id" INTEGER,"commercial_partner_id" INTEGER,"partner_shipping_id" INTEGER,"partner_bank_id" INTEGER,"fiscal_position_id" INTEGER,"currency_id" INTEGER,"reversed_entry_id" INTEGER,"campaign_id" INTEGER,"invoice_user_id" INTEGER,"statement_line_id" INTEGER,"invoice_incoterm_id" INTEGER,"preferred_payment_method_line_id" INTEGER,"invoice_cash_rounding_id" INTEGER,"creator_id" INTEGER,"sequence_prefix" varchar,"access_token" varchar,"name" varchar,"reference" varchar,"state" varchar,"move_type" varchar,"auto_post" tinyint(1),"inalterable_hash" varchar,"payment_reference" varchar,"qr_code_method" varchar,"payment_state" varchar,"invoice_source_email" varchar,"invoice_partner_display_name" varchar,"invoice_origin" varchar,"incoterm_location" varchar,"date" date,"auto_post_until" date,"invoice_date" date,"invoice_date_due" date,"delivery_date" date,"sending_data" TEXT,"narration" TEXT,"invoice_currency_rate" numeric,"amount_untaxed" numeric,"amount_tax" numeric,"amount_total" numeric,"amount_residual" numeric,"amount_untaxed_signed" numeric,"amount_untaxed_in_currency_signed" numeric,"amount_tax_signed" numeric,"amount_total_signed" numeric,"amount_total_in_currency_signed" numeric,"amount_residual_signed" numeric,"quick_edit_total_amount" numeric,"is_storno" tinyint(1),"always_tax_exigible" tinyint(1),"checked" tinyint(1),"posted_before" tinyint(1),"made_sequence_gap" tinyint(1),"is_manually_modified" tinyint(1),"is_move_sent" tinyint(1),"source_id" INTEGER,"medium_id" INTEGER,"created_at" datetime,"updated_at" datetime,"tax_cash_basis_reconcile_id" INTEGER);

CREATE TABLE "database.sqlite"."accounts_bank_statement_lines" ("id" INTEGER,"sort" INTEGER,"journal_id" INTEGER,"company_id" INTEGER,"statement_id" INTEGER,"partner_id" INTEGER,"currency_id" INTEGER,"foreign_currency_id" INTEGER,"created_by" INTEGER,"account_number" varchar,"partner_name" varchar,"transaction_type" varchar,"payment_reference" varchar,"internal_index" varchar,"transaction_details" TEXT,"amount" numeric,"amount_currency" numeric,"is_reconciled" tinyint(1),"amount_residual" numeric,"created_at" datetime,"updated_at" datetime,"move_id" INTEGER);

CREATE TABLE "database.sqlite"."accounts_bank_statements" ("id" INTEGER,"company_id" INTEGER,"journal_id" INTEGER,"created_by" INTEGER,"name" varchar,"reference" varchar,"first_line_index" varchar,"date" date,"balance_start" numeric,"balance_end" numeric,"balance_end_real" numeric,"is_completed" tinyint(1),"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."payments_payment_transactions" ("id" INTEGER,"sort" INTEGER,"move_id" INTEGER,"journal_id" INTEGER,"company_id" INTEGER,"statement_id" INTEGER,"partner_id" INTEGER,"currency_id" INTEGER,"foreign_currency_id" INTEGER,"created_id" INTEGER,"account_number" varchar,"partner_name" varchar,"transaction_type" varchar,"payment_reference" varchar,"internal_index" varchar,"transaction_details" TEXT,"amount" numeric,"amount_currency" numeric,"amount_residual" numeric,"is_reconciled" tinyint(1),"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_full_reconciles" ("id" INTEGER,"exchange_move_id" INTEGER,"created_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_partial_reconciles" ("id" INTEGER,"debit_move_id" INTEGER,"credit_move_id" INTEGER,"full_reconcile_id" INTEGER,"exchange_move_id" INTEGER,"debit_currency_id" INTEGER,"credit_currency_id" INTEGER,"company_id" INTEGER,"created_by" INTEGER,"max_date" date,"amount" numeric,"debit_amount_currency" numeric,"credit_amount_currency" numeric,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_accounts_move_reversal_move" ("move_id" INTEGER,"reversal_id" INTEGER);

CREATE TABLE "database.sqlite"."accounts_accounts_move_reversals" ("id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"reason" TEXT,"date" date,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_accounts_move_reversal_new_move" ("new_move_id" INTEGER,"reversal_id" INTEGER);

CREATE TABLE "database.sqlite"."utm_campaigns" ("id" INTEGER,"user_id" INTEGER,"stage_id" INTEGER,"color" varchar,"created_by" INTEGER,"name" varchar,"title" varchar,"is_active" tinyint(1),"is_auto_campaign" tinyint(1),"company_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."utm_stages" ("id" INTEGER,"sort" INTEGER,"name" varchar,"created_by" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."sales_orders" ("id" INTEGER,"utm_source_id" INTEGER,"campaign_id" INTEGER,"medium_id" INTEGER,"company_id" INTEGER,"partner_id" INTEGER,"journal_id" INTEGER,"partner_invoice_id" INTEGER,"partner_shipping_id" INTEGER,"fiscal_position_id" INTEGER,"payment_term_id" INTEGER,"currency_id" INTEGER,"user_id" INTEGER,"team_id" INTEGER,"creator_id" INTEGER,"sale_order_template_id" INTEGER,"access_token" varchar,"name" varchar,"state" varchar,"client_order_ref" varchar,"origin" varchar,"reference" varchar,"signed_by" varchar,"invoice_status" varchar,"validity_date" date,"note" TEXT,"currency_rate" numeric,"amount_untaxed" numeric,"amount_tax" numeric,"amount_total" numeric,"locked" tinyint(1),"require_signature" tinyint(1),"require_payment" tinyint(1),"commitment_date" date,"date_order" date,"signed_on" date,"prepayment_percent" numeric,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime,"delivery_status" varchar,"warehouse_id" INTEGER);

CREATE TABLE "database.sqlite"."sales_order_options" ("id" INTEGER,"sort" INTEGER,"order_id" INTEGER,"product_id" INTEGER,"line_id" INTEGER,"uom_id" INTEGER,"creator_id" INTEGER,"name" varchar,"quantity" numeric,"price_unit" numeric,"discount" numeric,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."unit_of_measures" ("id" INTEGER,"type" varchar,"name" varchar,"factor" numeric,"rounding" numeric,"category_id" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."unit_of_measure_categories" ("id" INTEGER,"name" varchar,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."inventories_lots" ("id" INTEGER,"name" varchar,"description" TEXT,"reference" varchar,"properties" TEXT,"expiry_reminded" tinyint(1),"expiration_date" datetime,"use_date" datetime,"removal_date" datetime,"alert_date" datetime,"product_id" INTEGER,"uom_id" INTEGER,"location_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."inventories_locations" ("id" INTEGER,"position_x" INTEGER,"position_y" INTEGER,"position_z" INTEGER,"type" varchar,"name" varchar,"full_name" varchar,"description" varchar,"parent_path" varchar,"barcode" varchar,"removal_strategy" varchar,"cyclic_inventory_frequency" INTEGER,"last_inventory_date" date,"next_inventory_date" date,"is_scrap" tinyint(1),"is_replenish" tinyint(1),"is_dock" tinyint(1),"parent_id" INTEGER,"company_id" INTEGER,"storage_category_id" INTEGER,"warehouse_id" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."inventories_warehouses" ("id" INTEGER,"name" varchar,"code" varchar,"sort" INTEGER,"reception_steps" varchar,"delivery_steps" varchar,"company_id" INTEGER,"partner_address_id" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime,"view_location_id" INTEGER,"lot_stock_location_id" INTEGER,"input_stock_location_id" INTEGER,"qc_stock_location_id" INTEGER,"output_stock_location_id" INTEGER,"pack_stock_location_id" INTEGER,"mto_pull_id" INTEGER,"buy_pull_id" INTEGER,"pick_type_id" INTEGER,"pack_type_id" INTEGER,"out_type_id" INTEGER,"in_type_id" INTEGER,"internal_type_id" INTEGER,"qc_type_id" INTEGER,"store_type_id" INTEGER,"xdock_type_id" INTEGER,"crossdock_route_id" INTEGER,"reception_route_id" INTEGER,"delivery_route_id" INTEGER);

CREATE TABLE "database.sqlite"."inventories_operation_types" ("id" INTEGER,"name" varchar,"type" varchar,"sort" INTEGER,"sequence_code" varchar,"reservation_method" varchar,"reservation_days_before" INTEGER,"reservation_days_before_priority" INTEGER,"product_label_format" varchar,"lot_label_format" varchar,"package_label_to_print" varchar,"barcode" varchar,"create_backorder" varchar,"move_type" varchar,"show_entire_packs" tinyint(1),"use_create_lots" tinyint(1),"use_existing_lots" tinyint(1),"print_label" tinyint(1),"show_operations" tinyint(1),"auto_show_reception_report" tinyint(1),"auto_print_delivery_slip" tinyint(1),"auto_print_return_slip" tinyint(1),"auto_print_product_labels" tinyint(1),"auto_print_lot_labels" tinyint(1),"auto_print_reception_report" tinyint(1),"auto_print_reception_report_labels" tinyint(1),"auto_print_packages" tinyint(1),"auto_print_package_label" tinyint(1),"return_operation_type_id" INTEGER,"source_location_id" INTEGER,"destination_location_id" INTEGER,"warehouse_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."inventories_rules" ("id" INTEGER,"sort" INTEGER,"name" varchar,"route_sort" INTEGER,"delay" INTEGER,"group_propagation_option" varchar,"action" varchar,"procure_method" varchar,"auto" varchar,"push_domain" varchar,"location_dest_from_rule" tinyint(1),"propagate_cancel" tinyint(1),"propagate_carrier" tinyint(1),"source_location_id" INTEGER,"destination_location_id" INTEGER,"route_id" INTEGER,"operation_type_id" INTEGER,"partner_address_id" INTEGER,"warehouse_id" INTEGER,"propagate_warehouse_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."inventories_routes" ("id" INTEGER,"sort" INTEGER,"name" varchar,"product_selectable" tinyint(1),"product_category_selectable" tinyint(1),"warehouse_selectable" tinyint(1),"packaging_selectable" tinyint(1),"supplied_warehouse_id" INTEGER,"supplier_warehouse_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."inventories_route_warehouses" ("warehouse_id" INTEGER,"route_id" INTEGER);

CREATE TABLE "database.sqlite"."inventories_category_routes" ("category_id" INTEGER,"route_id" INTEGER);

CREATE TABLE "database.sqlite"."products_categories" ("id" INTEGER,"name" varchar,"full_name" varchar,"parent_path" varchar,"parent_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime,"product_properties_definition" TEXT);

CREATE TABLE "database.sqlite"."inventories_order_points" ("id" INTEGER,"name" varchar,"trigger" varchar,"snoozed_until" date,"product_min_qty" numeric,"product_max_qty" numeric,"qty_multiple" numeric,"qty_to_order_manual" numeric,"product_id" INTEGER,"product_category_id" INTEGER,"warehouse_id" INTEGER,"location_id" INTEGER,"route_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."purchases_order_lines" ("id" INTEGER,"name" TEXT,"state" varchar,"sort" INTEGER,"qty_received_method" varchar,"display_type" varchar,"product_qty" numeric,"product_uom_qty" double,"product_packaging_qty" double,"price_tax" double,"discount" numeric,"price_unit" numeric,"price_subtotal" numeric,"price_total" numeric,"qty_invoiced" numeric,"qty_received" numeric,"qty_received_manual" numeric,"qty_to_invoice" numeric,"is_downpayment" tinyint(1),"planned_at" datetime,"product_description_variants" varchar,"propagate_cancel" tinyint(1),"price_total_cc" numeric,"uom_id" INTEGER,"product_id" INTEGER,"product_packaging_id" INTEGER,"order_id" INTEGER,"partner_id" INTEGER,"currency_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime,"final_location_id" INTEGER,"order_point_id" INTEGER);

CREATE TABLE "database.sqlite"."purchases_order_line_taxes" ("order_line_id" INTEGER,"tax_id" INTEGER);

CREATE TABLE "database.sqlite"."products_packagings" ("id" INTEGER,"name" varchar,"barcode" varchar,"qty" numeric,"sort" INTEGER,"product_id" INTEGER,"creator_id" INTEGER,"company_id" INTEGER,"created_at" datetime,"updated_at" datetime,"package_type_id" INTEGER);

CREATE TABLE "database.sqlite"."inventories_package_types" ("id" INTEGER,"sort" INTEGER,"name" varchar,"barcode" varchar,"height" numeric,"width" numeric,"length" numeric,"base_weight" numeric,"max_weight" numeric,"shipper_package_code" varchar,"package_carrier_type" varchar,"company_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."inventories_packages" ("id" INTEGER,"name" varchar,"package_use" varchar,"pack_date" date,"package_type_id" INTEGER,"location_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."inventories_product_quantities" ("id" INTEGER,"quantity" numeric,"reserved_quantity" numeric,"counted_quantity" numeric,"difference_quantity" numeric,"inventory_diff_quantity" numeric,"inventory_quantity_set" tinyint(1),"scheduled_at" date,"incoming_at" datetime,"product_id" INTEGER,"location_id" INTEGER,"storage_category_id" INTEGER,"lot_id" INTEGER,"package_id" INTEGER,"partner_id" INTEGER,"user_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."inventories_storage_categories" ("id" INTEGER,"name" varchar,"sort" INTEGER,"allow_new_products" varchar,"max_weight" numeric,"company_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."inventories_storage_category_capacities" ("id" INTEGER,"qty" numeric,"product_id" INTEGER,"storage_category_id" INTEGER,"package_type_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."inventories_product_quantity_relocations" ("id" INTEGER,"description" TEXT,"destination_location_id" INTEGER,"destination_package_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."inventories_package_levels" ("id" INTEGER,"package_id" INTEGER,"operation_id" INTEGER,"destination_location_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."inventories_operations" ("id" INTEGER,"name" varchar,"description" TEXT,"origin" varchar,"move_type" varchar,"state" varchar,"is_favorite" tinyint(1),"has_deadline_issue" tinyint(1),"is_printed" tinyint(1),"is_locked" tinyint(1),"deadline" datetime,"scheduled_at" datetime,"closed_at" datetime,"user_id" INTEGER,"owner_id" INTEGER,"operation_type_id" INTEGER,"source_location_id" INTEGER,"destination_location_id" INTEGER,"back_order_id" INTEGER,"return_id" INTEGER,"partner_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime,"sale_order_id" INTEGER);

CREATE TABLE "database.sqlite"."inventories_package_destinations" ("id" INTEGER,"operation_id" INTEGER,"destination_location_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."inventories_scraps" ("id" INTEGER,"name" varchar,"origin" varchar,"state" varchar,"qty" numeric,"should_replenish" tinyint(1),"closed_at" date,"product_id" INTEGER,"uom_id" INTEGER,"lot_id" INTEGER,"package_id" INTEGER,"partner_id" INTEGER,"operation_id" INTEGER,"source_location_id" INTEGER,"destination_location_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."inventories_scrap_tags" ("tag_id" INTEGER,"scrap_id" INTEGER);

CREATE TABLE "database.sqlite"."inventories_tags" ("id" INTEGER,"name" varchar,"color" varchar,"sort" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."inventories_moves" ("id" INTEGER,"name" varchar,"state" varchar,"origin" varchar,"procure_method" varchar,"reference" varchar,"description_picking" TEXT,"next_serial" varchar,"next_serial_count" INTEGER,"is_favorite" tinyint(1),"product_qty" numeric,"product_uom_qty" numeric,"quantity" numeric,"is_picked" tinyint(1),"is_scraped" tinyint(1),"is_inventory" tinyint(1),"reservation_date" date,"scheduled_at" datetime,"deadline" datetime,"alert_Date" datetime,"operation_id" INTEGER,"product_id" INTEGER,"uom_id" INTEGER,"source_location_id" INTEGER,"destination_location_id" INTEGER,"final_location_id" INTEGER,"partner_id" INTEGER,"scrap_id" INTEGER,"rule_id" INTEGER,"operation_type_id" INTEGER,"origin_returned_move_id" INTEGER,"restrict_partner_id" INTEGER,"warehouse_id" INTEGER,"package_level_id" INTEGER,"product_packaging_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime,"is_refund" tinyint(1),"purchase_order_line_id" INTEGER,"sale_order_line_id" INTEGER);

CREATE TABLE "database.sqlite"."inventories_move_destinations" ("origin_move_id" INTEGER,"destination_move_id" INTEGER);

CREATE TABLE "database.sqlite"."inventories_move_lines" ("id" INTEGER,"lot_name" varchar,"state" varchar,"reference" varchar,"picking_description" varchar,"qty" numeric,"uom_qty" numeric,"is_picked" tinyint(1),"scheduled_at" datetime,"move_id" INTEGER,"operation_id" INTEGER,"product_id" INTEGER,"uom_id" INTEGER,"package_id" INTEGER,"result_package_id" INTEGER,"package_level_id" INTEGER,"lot_id" INTEGER,"partner_id" INTEGER,"source_location_id" INTEGER,"destination_location_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."sales_order_lines" ("id" INTEGER,"sort" INTEGER,"order_id" INTEGER,"company_id" INTEGER,"currency_id" INTEGER,"order_partner_id" INTEGER,"salesman_id" INTEGER,"product_id" INTEGER,"product_uom_id" INTEGER,"linked_sale_order_sale_id" INTEGER,"product_packaging_id" INTEGER,"creator_id" INTEGER,"state" varchar,"display_type" varchar,"virtual_id" varchar,"linked_virtual_id" varchar,"qty_delivered_method" varchar,"invoice_status" varchar,"analytic_distribution" varchar,"name" varchar,"product_uom_qty" numeric,"product_qty" numeric,"price_unit" numeric,"discount" numeric,"price_subtotal" numeric,"price_total" numeric,"price_reduce_taxexcl" numeric,"price_reduce_taxinc" numeric,"purchase_price" numeric,"margin" numeric,"margin_percent" numeric,"qty_delivered" numeric,"qty_invoiced" numeric,"qty_to_invoice" numeric,"untaxed_amount_invoiced" numeric,"untaxed_amount_to_invoice" numeric,"is_downpayment" tinyint(1),"is_expense" tinyint(1),"create_date" datetime,"write_date" datetime,"technical_price_unit" numeric,"price_tax" numeric,"product_packaging_qty" numeric,"customer_lead" numeric,"created_at" datetime,"updated_at" datetime,"route_id" INTEGER,"warehouse_id" INTEGER);

CREATE TABLE "database.sqlite"."sales_order_line_taxes" ("order_line_id" INTEGER,"tax_id" INTEGER);

CREATE TABLE "database.sqlite"."sales_order_line_invoices" ("order_line_id" INTEGER,"invoice_line_id" INTEGER);

CREATE TABLE "database.sqlite"."purchases_order_operations" ("purchase_order_id" INTEGER,"inventory_operation_id" INTEGER);

CREATE TABLE "database.sqlite"."purchases_orders" ("id" INTEGER,"name" varchar,"description" TEXT,"priority" varchar,"origin" varchar,"partner_reference" varchar,"state" varchar,"invoice_status" varchar,"receipt_status" varchar,"untaxed_amount" numeric,"tax_amount" numeric,"total_amount" numeric,"total_cc_amount" numeric,"currency_rate" numeric,"invoice_count" INTEGER,"ordered_at" datetime,"approved_at" datetime,"planned_at" datetime,"calendar_start_at" datetime,"effective_date" datetime,"incoterm_location" varchar,"mail_reminder_confirmed" tinyint(1),"mail_reception_confirmed" tinyint(1),"mail_reception_declined" tinyint(1),"report_grids" tinyint(1),"requisition_id" INTEGER,"purchases_group_id" INTEGER,"partner_id" INTEGER,"currency_id" INTEGER,"fiscal_position_id" INTEGER,"payment_term_id" INTEGER,"incoterm_id" INTEGER,"user_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime,"operation_type_id" INTEGER);

CREATE TABLE "database.sqlite"."purchases_order_account_moves" ("order_id" INTEGER,"move_id" INTEGER);

CREATE TABLE "database.sqlite"."purchases_order_groups" ("id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_incoterms" ("id" INTEGER,"creator_id" INTEGER,"code" varchar,"name" varchar,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_fiscal_positions" ("id" INTEGER,"sort" INTEGER,"company_id" INTEGER,"country_id" INTEGER,"country_group_id" INTEGER,"creator_id" INTEGER,"zip_from" varchar,"zip_to" varchar,"foreign_vat" varchar,"name" varchar,"notes" TEXT,"auto_reply" tinyint(1),"vat_required" tinyint(1),"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_fiscal_position_taxes" ("id" INTEGER,"fiscal_position_id" INTEGER,"company_id" INTEGER,"tax_source_id" INTEGER,"tax_destination_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."purchases_requisitions" ("id" INTEGER,"name" varchar,"type" varchar,"state" varchar,"reference" varchar,"starts_at" date,"ends_at" date,"description" TEXT,"currency_id" INTEGER,"partner_id" INTEGER,"user_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."purchases_requisition_lines" ("id" INTEGER,"qty" numeric,"price_unit" numeric,"requisition_id" INTEGER,"product_id" INTEGER,"uom_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_payment_terms" ("id" INTEGER,"company_id" INTEGER,"sort" INTEGER,"discount_days" INTEGER,"creator_id" INTEGER,"early_pay_discount" varchar,"name" varchar,"note" varchar,"display_on_invoice" tinyint(1),"early_discount" tinyint(1),"discount_percentage" numeric,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_payment_due_terms" ("id" INTEGER,"nb_days" INTEGER,"payment_id" INTEGER,"creator_id" INTEGER,"value" varchar,"delay_type" varchar,"days_next_month" varchar,"value_amount" numeric,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."inventories_route_packagings" ("route_id" INTEGER,"packaging_id" INTEGER);

CREATE TABLE "database.sqlite"."inventories_product_routes" ("product_id" INTEGER,"route_id" INTEGER);

CREATE TABLE "database.sqlite"."inventories_warehouse_resupplies" ("supplied_warehouse_id" INTEGER,"supplier_warehouse_id" INTEGER);

CREATE TABLE "database.sqlite"."sales_order_template_products" ("id" INTEGER,"sort" INTEGER,"order_template_id" INTEGER,"company_id" INTEGER,"product_id" INTEGER,"product_uom_id" INTEGER,"creator_id" INTEGER,"display_type" varchar,"name" varchar,"quantity" numeric,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."sales_order_templates" ("id" INTEGER,"sort" INTEGER,"company_id" INTEGER,"number_of_days" INTEGER,"creator_id" INTEGER,"name" varchar,"note" TEXT,"journal_id" INTEGER,"is_active" tinyint(1),"require_signature" tinyint(1),"require_payment" tinyint(1),"prepayment_percentage" numeric,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."sales_order_tags" ("order_id" INTEGER,"tag_id" INTEGER);

CREATE TABLE "database.sqlite"."sales_tags" ("id" INTEGER,"creator_id" INTEGER,"name" varchar,"color" varchar,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."sales_advance_payment_invoice_order_sales" ("advance_payment_invoice_id" INTEGER,"order_id" INTEGER);

CREATE TABLE "database.sqlite"."sales_advance_payment_invoices" ("id" INTEGER,"currency_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"advance_payment_method" varchar,"fixed_amount" numeric,"amount" numeric,"deduct_down_payments" tinyint(1),"consolidated_billing" tinyint(1),"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."sales_order_invoices" ("order_id" INTEGER,"move_id" INTEGER);

CREATE TABLE "database.sqlite"."utm_sources" ("id" INTEGER,"creator_id" INTEGER,"name" varchar,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."recruitments_applicants" ("id" INTEGER,"source_id" INTEGER,"medium_id" INTEGER,"candidate_id" INTEGER,"stage_id" INTEGER,"last_stage_id" INTEGER,"company_id" INTEGER,"recruiter_id" INTEGER,"job_id" INTEGER,"department_id" INTEGER,"refuse_reason_id" INTEGER,"creator_id" INTEGER,"email_cc" varchar,"priority" varchar,"salary_proposed_extra" varchar,"salary_expected_extra" varchar,"applicant_properties" TEXT,"applicant_notes" TEXT,"is_active" tinyint(1),"state" varchar,"create_date" datetime,"date_closed" datetime,"date_opened" datetime,"date_last_stage_updated" datetime,"refuse_date" datetime,"probability" numeric,"salary_proposed" numeric,"salary_expected" numeric,"delay_close" numeric,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."recruitments_stages" ("id" INTEGER,"sort" INTEGER,"creator_id" INTEGER,"name" varchar,"legend_blocked" varchar,"legend_done" varchar,"legend_normal" varchar,"requirements" TEXT,"hired_stage" varchar,"fold" tinyint(1),"created_at" datetime,"updated_at" datetime,"is_default" tinyint(1));

CREATE TABLE "database.sqlite"."recruitments_stages_jobs" ("stage_id" INTEGER,"job_id" INTEGER);

CREATE TABLE "database.sqlite"."employees_job_positions" ("id" INTEGER,"sort" INTEGER,"expected_employees" INTEGER,"no_of_employee" INTEGER,"no_of_recruitment" INTEGER,"department_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"employment_type_id" INTEGER,"name" varchar,"description" TEXT,"requirements" TEXT,"is_active" tinyint(1),"deleted_at" datetime,"created_at" datetime,"updated_at" datetime,"address_id" INTEGER,"manager_id" INTEGER,"industry_id" INTEGER,"recruiter_id" INTEGER,"no_of_hired_employee" INTEGER,"date_from" datetime,"date_to" datetime);

CREATE TABLE "database.sqlite"."job_position_skills" ("job_position_id" INTEGER,"skill_id" INTEGER);

CREATE TABLE "database.sqlite"."employees_skills" ("id" INTEGER,"sort" INTEGER,"name" varchar,"skill_type_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime,"deleted_at" datetime);

CREATE TABLE "database.sqlite"."employees_skill_types" ("id" INTEGER,"name" varchar,"color" varchar,"is_active" tinyint(1),"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime,"deleted_at" datetime);

CREATE TABLE "database.sqlite"."employees_skill_levels" ("id" INTEGER,"name" varchar,"level" INTEGER,"default_level" tinyint(1),"skill_type_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime,"deleted_at" datetime);

CREATE TABLE "database.sqlite"."employees_employee_skills" ("id" INTEGER,"employee_id" INTEGER,"skill_id" INTEGER,"skill_level_id" INTEGER,"skill_type_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime,"deleted_at" datetime);

CREATE TABLE "database.sqlite"."recruitments_candidate_skills" ("id" INTEGER,"candidate_id" INTEGER,"skill_id" INTEGER,"skill_level_id" INTEGER,"skill_type_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."recruitments_candidates" ("id" INTEGER,"message_bounced" INTEGER,"company_id" INTEGER,"partner_id" INTEGER,"degree_id" INTEGER,"manager_id" INTEGER,"employee_id" INTEGER,"creator_id" INTEGER,"email_cc" varchar,"name" varchar,"email_from" varchar,"phone" varchar,"linkedin_profile" varchar,"priority" INTEGER,"availability_date" date,"candidate_properties" TEXT,"is_active" tinyint(1),"color" varchar,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."recruitments_degrees" ("id" INTEGER,"creator_id" INTEGER,"sort" INTEGER,"name" varchar,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."recruitments_candidate_applicant_categories" ("candidate_id" INTEGER,"category_id" INTEGER);

CREATE TABLE "database.sqlite"."recruitments_applicant_categories" ("id" INTEGER,"creator_id" INTEGER,"name" varchar,"color" varchar,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."recruitments_applicant_applicant_categories" ("applicant_id" INTEGER,"category_id" INTEGER);

CREATE TABLE "database.sqlite"."employees_employment_types" ("id" INTEGER,"sort" INTEGER,"name" varchar,"code" varchar,"country_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."partners_industries" ("id" INTEGER,"name" varchar,"description" TEXT,"is_active" tinyint(1),"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."recruitments_job_position_interviewers" ("job_position_id" INTEGER,"user_id" INTEGER);

CREATE TABLE "database.sqlite"."utm_mediums" ("id" INTEGER,"creator_id" INTEGER,"name" varchar,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."recruitments_refuse_reasons" ("id" INTEGER,"creator_id" INTEGER,"sort" INTEGER,"name" varchar,"template" varchar,"is_active" tinyint(1),"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."recruitments_applicant_interviewers" ("applicant_id" INTEGER,"interviewer_id" INTEGER);

CREATE TABLE "database.sqlite"."sales_teams" ("id" INTEGER,"sort" INTEGER,"company_id" INTEGER,"user_id" INTEGER,"color" varchar,"creator_id" INTEGER,"name" varchar,"is_active" tinyint(1),"invoiced_target" numeric,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."sales_team_members" ("team_id" INTEGER,"user_id" INTEGER);

CREATE TABLE "database.sqlite"."accounts_cash_roundings" ("id" INTEGER,"creator_id" INTEGER,"strategy" varchar,"rounding_method" varchar,"name" varchar,"rounding" numeric,"profit_account_id" INTEGER,"loss_account_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."payments_payment_tokens" ("id" INTEGER,"company_id" INTEGER,"payment_method_id" INTEGER,"partner_id" INTEGER,"created_by" INTEGER,"payment_details" TEXT,"provider_reference_id" varchar,"is_active" tinyint(1),"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."payments_payment_methods" ("id" INTEGER,"sort" INTEGER,"primary_payment_method_id" INTEGER,"created_by" INTEGER,"code" varchar,"support_refund" varchar,"name" varchar,"is_active" tinyint(1),"support_tokenization" tinyint(1),"support_express_checkout" tinyint(1),"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_accounts_move_line_taxes" ("move_line_id" INTEGER,"tax_id" INTEGER);

CREATE TABLE "database.sqlite"."accounts_tax_partition_lines" ("id" INTEGER,"account_id" INTEGER,"tax_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"sort" INTEGER,"repartition_type" varchar,"document_type" varchar,"use_in_tax_closing" varchar,"factor" numeric,"factor_percent" numeric,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_reconciles" ("id" INTEGER,"sort" INTEGER,"company_id" INTEGER,"past_months_limit" INTEGER,"created_by" INTEGER,"rule_type" varchar,"matching_order" varchar,"counter_part_type" varchar,"match_nature" varchar,"match_amount" varchar,"match_label" varchar,"match_level_parameters" varchar,"match_note" varchar,"match_note_parameters" varchar,"match_transaction_type" varchar,"match_transaction_type_parameters" varchar,"payment_tolerance_type" varchar,"decimal_separator" varchar,"name" varchar,"auto_reconcile" tinyint(1),"to_check" tinyint(1),"match_text_location_label" tinyint(1),"match_text_location_note" tinyint(1),"match_text_location_reference" tinyint(1),"match_same_currency" tinyint(1),"allow_payment_tolerance" tinyint(1),"match_partner" tinyint(1),"match_amount_min" numeric,"match_amount_max" numeric,"payment_tolerance_parameters" numeric,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_tax_taxes" ("parent_tax_id" INTEGER,"child_tax_id" INTEGER);

CREATE TABLE "database.sqlite"."accounts_account_taxes" ("account_id" INTEGER,"tax_id" INTEGER);

CREATE TABLE "database.sqlite"."accounts_product_taxes" ("product_id" INTEGER,"tax_id" INTEGER);

CREATE TABLE "database.sqlite"."accounts_product_supplier_taxes" ("product_id" INTEGER,"tax_id" INTEGER);

CREATE TABLE "database.sqlite"."accounts_journal_accounts" ("journal_id" INTEGER,"account_id" INTEGER);

CREATE TABLE "database.sqlite"."accounts_account_account_tags" ("account_id" INTEGER,"account_tag_id" INTEGER);

CREATE TABLE "database.sqlite"."accounts_account_tags" ("id" INTEGER,"color" varchar,"country_id" INTEGER,"creator_id" INTEGER,"applicability" varchar,"name" varchar,"tax_negate" tinyint(1),"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."accounts_account_journals" ("account_id" INTEGER,"journal_id" INTEGER);

CREATE TABLE "database.sqlite"."partners_partner_tag" ("tag_id" INTEGER,"partner_id" INTEGER);

CREATE TABLE "database.sqlite"."partners_tags" ("id" INTEGER,"name" varchar,"color" varchar,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."partners_titles" ("id" INTEGER,"name" varchar,"short_name" varchar,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."projects_projects" ("id" INTEGER,"name" varchar,"tasks_label" varchar,"description" TEXT,"visibility" varchar,"color" varchar,"sort" INTEGER,"start_date" date,"end_date" date,"allocated_hours" numeric,"allow_timesheets" tinyint(1),"allow_milestones" tinyint(1),"allow_task_dependencies" tinyint(1),"is_active" tinyint(1),"stage_id" INTEGER,"partner_id" INTEGER,"company_id" INTEGER,"user_id" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."projects_project_stages" ("id" INTEGER,"name" varchar,"tags" TEXT,"is_active" tinyint(1),"is_collapsed" tinyint(1),"sort" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."projects_milestones" ("id" INTEGER,"name" varchar,"deadline" datetime,"is_completed" tinyint(1),"completed_at" datetime,"project_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."projects_tasks" ("id" INTEGER,"title" varchar,"description" TEXT,"color" varchar,"priority" tinyint(1),"state" varchar,"tags" TEXT,"sort" INTEGER,"is_active" tinyint(1),"is_recurring" tinyint(1),"deadline" datetime,"working_hours_open" numeric,"working_hours_close" numeric,"allocated_hours" numeric,"remaining_hours" numeric,"effective_hours" numeric,"total_hours_spent" numeric,"overtime" numeric,"progress" numeric,"subtask_effective_hours" numeric,"project_id" INTEGER,"milestone_id" INTEGER,"stage_id" INTEGER,"partner_id" INTEGER,"parent_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."projects_task_stages" ("id" INTEGER,"name" varchar,"is_active" tinyint(1),"is_collapsed" tinyint(1),"sort" INTEGER,"project_id" INTEGER,"company_id" INTEGER,"user_id" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."projects_task_users" ("id" INTEGER,"task_id" INTEGER,"user_id" INTEGER,"stage_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."projects_task_tag" ("tag_id" INTEGER,"task_id" INTEGER);

CREATE TABLE "database.sqlite"."projects_tags" ("id" INTEGER,"name" varchar,"color" varchar,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."projects_project_tag" ("tag_id" INTEGER,"project_id" INTEGER);

CREATE TABLE "database.sqlite"."analytic_records" ("id" INTEGER,"type" varchar,"name" varchar,"date" date,"amount" numeric,"unit_amount" numeric,"user_id" INTEGER,"partner_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime,"project_id" INTEGER,"task_id" INTEGER);

CREATE TABLE "database.sqlite"."projects_user_project_favorites" ("project_id" INTEGER,"user_id" INTEGER);

CREATE TABLE "database.sqlite"."products_product_price_lists" ("id" INTEGER,"sort" INTEGER,"currency_id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"name" varchar,"is_active" tinyint(1),"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."employees_departure_reasons" ("id" INTEGER,"sort" INTEGER,"reason_code" INTEGER,"name" varchar,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."employees_calendars" ("id" INTEGER,"name" varchar,"timezone" varchar,"hours_per_day" float,"is_active" tinyint(1),"two_weeks_calendar" tinyint(1),"flexible_hours" tinyint(1),"full_time_required_hours" float,"creator_id" INTEGER,"company_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."employees_calendar_attendances" ("id" INTEGER,"sort" INTEGER,"name" varchar,"day_of_week" varchar,"day_period" varchar,"week_type" varchar,"display_type" varchar,"date_from" varchar,"date_to" varchar,"duration_days" varchar,"hour_from" varchar,"hour_to" varchar,"calendar_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."employees_calendar_leaves" ("id" INTEGER,"name" varchar,"time_type" varchar,"date_from" varchar,"date_to" varchar,"company_id" INTEGER,"calendar_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."employees_work_locations" ("id" INTEGER,"name" varchar,"location_type" varchar,"location_number" varchar,"is_active" tinyint(1),"company_id" INTEGER,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime,"deleted_at" datetime);

CREATE TABLE "database.sqlite"."employees_employee_categories" ("employee_id" INTEGER,"category_id" INTEGER);

CREATE TABLE "database.sqlite"."employees_categories" ("id" INTEGER,"name" varchar,"color" varchar,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."employees_employee_resumes" ("id" INTEGER,"employee_id" INTEGER,"employee_resume_line_type_id" INTEGER,"creator_id" INTEGER,"user_id" INTEGER,"display_type" varchar,"start_date" date,"end_date" date,"name" varchar,"description" TEXT,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."employees_employee_resume_line_types" ("id" INTEGER,"sort" INTEGER,"name" varchar,"creator_id" INTEGER,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."activity_type_suggestions" ("activity_type_id" INTEGER,"suggested_activity_type_id" INTEGER);

CREATE TABLE "database.sqlite"."chatter_attachments" ("id" INTEGER,"company_id" INTEGER,"creator_id" INTEGER,"message_id" INTEGER,"file_size" varchar,"name" varchar,"messageable_type" varchar,"messageable_id" INTEGER,"file_path" varchar,"original_file_name" varchar,"mime_type" varchar,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."website_pages" ("id" INTEGER,"title" varchar,"content" TEXT,"slug" varchar,"is_published" tinyint(1),"is_header_visible" tinyint(1),"is_footer_visible" tinyint(1),"published_at" datetime,"meta_title" varchar,"meta_keywords" varchar,"meta_description" TEXT,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."blogs_categories" ("id" INTEGER,"name" varchar,"sub_title" TEXT,"slug" varchar,"image" varchar,"meta_title" varchar,"meta_keywords" varchar,"meta_description" TEXT,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."blogs_posts" ("id" INTEGER,"title" varchar,"sub_title" TEXT,"content" TEXT,"slug" varchar,"image" varchar,"author_name" varchar,"is_published" tinyint(1),"published_at" datetime,"visits" INTEGER,"meta_title" TEXT,"meta_keywords" TEXT,"meta_description" TEXT,"category_id" INTEGER,"author_id" INTEGER,"creator_id" INTEGER,"last_editor_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."blogs_post_tags" ("tag_id" INTEGER,"post_id" INTEGER);

CREATE TABLE "database.sqlite"."blogs_tags" ("id" INTEGER,"name" varchar,"color" varchar,"sort" INTEGER,"creator_id" INTEGER,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."custom_fields" ("id" INTEGER,"code" varchar,"name" varchar,"type" varchar,"input_type" varchar,"is_multiselect" tinyint(1),"datalist" TEXT,"options" TEXT,"form_settings" TEXT,"use_in_table" tinyint(1),"table_settings" TEXT,"infolist_settings" TEXT,"sort" INTEGER,"customizable_type" varchar,"deleted_at" datetime,"created_at" datetime,"updated_at" datetime);

CREATE TABLE "database.sqlite"."email_logs" ("id" INTEGER,"recipient_email" varchar,"recipient_name" varchar,"subject" varchar,"status" varchar,"error_message" TEXT,"sent_at" datetime,"created_at" datetime,"updated_at" datetime);

ALTER TABLE "database.sqlite"."permissions" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."model_has_permissions" ("permission_id");

ALTER TABLE "database.sqlite"."roles" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."model_has_roles" ("role_id");

ALTER TABLE "database.sqlite"."roles" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."role_has_permissions" ("role_id");

ALTER TABLE "database.sqlite"."permissions" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."role_has_permissions" ("permission_id");

ALTER TABLE "database.sqlite"."plugins" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."plugin_dependencies" ("plugin_id");

ALTER TABLE "database.sqlite"."plugins" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."plugin_dependencies" ("dependency_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."user_team" ("user_id");

ALTER TABLE "database.sqlite"."teams" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."user_team" ("team_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."table_views" ("user_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."table_view_favorites" ("user_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."countries" ("currency_id");

ALTER TABLE "database.sqlite"."countries" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."states" ("country_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."user_allowed_companies" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."user_allowed_companies" ("user_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."banks" ("creator_id");

ALTER TABLE "database.sqlite"."states" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."banks" ("state_id");

ALTER TABLE "database.sqlite"."countries" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."banks" ("country_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_industries" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_titles" ("creator_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."chatter_followers" ("partner_id");

ALTER TABLE "database.sqlite"."banks" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_bank_accounts" ("bank_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_bank_accounts" ("creator_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_bank_accounts" ("partner_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_tags" ("creator_id");

ALTER TABLE "database.sqlite"."partners_tags" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_partner_tag" ("tag_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_partner_tag" ("partner_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."activity_types" ("default_user_id");

ALTER TABLE "database.sqlite"."activity_plans" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."activity_types" ("activity_plan_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."activity_types" ("creator_id");

ALTER TABLE "database.sqlite"."activity_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."activity_types" ("triggered_next_type_id");

ALTER TABLE "database.sqlite"."activity_plans" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."activity_plan_templates" ("plan_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."activity_plan_templates" ("responsible_id");

ALTER TABLE "database.sqlite"."activity_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."activity_plan_templates" ("activity_type_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."activity_plan_templates" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."users" ("default_company_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."users" ("partner_id");

ALTER TABLE "database.sqlite"."activity_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."activity_type_suggestions" ("activity_type_id");

ALTER TABLE "database.sqlite"."activity_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."activity_type_suggestions" ("suggested_activity_type_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."chatter_messages" ("assigned_to");

ALTER TABLE "database.sqlite"."activity_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."chatter_messages" ("activity_type_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."chatter_messages" ("company_id");

ALTER TABLE "database.sqlite"."chatter_messages" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."chatter_attachments" ("message_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."chatter_attachments" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."chatter_attachments" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."unit_of_measure_categories" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."unit_of_measures" ("creator_id");

ALTER TABLE "database.sqlite"."unit_of_measure_categories" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."unit_of_measures" ("category_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."utm_mediums" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."utm_sources" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."utm_stages" ("created_by");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."utm_campaigns" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."utm_campaigns" ("user_id");

ALTER TABLE "database.sqlite"."utm_stages" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."utm_campaigns" ("stage_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."utm_campaigns" ("created_by");

ALTER TABLE "database.sqlite"."states" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."companies" ("state_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."companies" ("parent_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."companies" ("creator_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."companies" ("partner_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."companies" ("currency_id");

ALTER TABLE "database.sqlite"."countries" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."companies" ("country_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."website_pages" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."blogs_categories" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."blogs_posts" ("creator_id");

ALTER TABLE "database.sqlite"."blogs_categories" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."blogs_posts" ("category_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."blogs_posts" ("author_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."blogs_posts" ("last_editor_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."blogs_tags" ("creator_id");

ALTER TABLE "database.sqlite"."blogs_posts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."blogs_post_tags" ("post_id");

ALTER TABLE "database.sqlite"."blogs_tags" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."blogs_post_tags" ("tag_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_categories" ("creator_id");

ALTER TABLE "database.sqlite"."products_categories" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_categories" ("parent_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_tags" ("creator_id");

ALTER TABLE "database.sqlite"."products_tags" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_tag" ("tag_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_tag" ("product_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_attributes" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_attribute_options" ("creator_id");

ALTER TABLE "database.sqlite"."products_attributes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_attribute_options" ("attribute_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_attributes" ("product_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_attributes" ("creator_id");

ALTER TABLE "database.sqlite"."products_attributes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_attributes" ("attribute_id");

ALTER TABLE "database.sqlite"."products_product_attributes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_attribute_values" ("product_attribute_id");

ALTER TABLE "database.sqlite"."products_attributes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_attribute_values" ("attribute_id");

ALTER TABLE "database.sqlite"."products_attribute_options" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_attribute_values" ("attribute_option_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_attribute_values" ("product_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_price_rules" ("currency_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_price_rules" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_price_rules" ("company_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_price_rule_items" ("product_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_price_rule_items" ("creator_id");

ALTER TABLE "database.sqlite"."products_categories" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_price_rule_items" ("category_id");

ALTER TABLE "database.sqlite"."products_price_rules" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_price_rule_items" ("price_rule_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_price_rule_items" ("company_id");

ALTER TABLE "database.sqlite"."products_price_rules" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_price_rule_items" ("base_price_rule_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_price_rule_items" ("currency_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_suppliers" ("creator_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_suppliers" ("currency_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_suppliers" ("product_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_suppliers" ("company_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_suppliers" ("partner_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_price_lists" ("currency_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_price_lists" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_price_lists" ("company_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_combinations" ("product_id");

ALTER TABLE "database.sqlite"."products_product_attribute_values" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_product_combinations" ("product_attribute_value_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_terms" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_terms" ("company_id");

ALTER TABLE "database.sqlite"."accounts_payment_terms" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_due_terms" ("payment_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_due_terms" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_incoterms" ("creator_id");

ALTER TABLE "database.sqlite"."countries" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_tax_groups" ("country_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_tax_groups" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_tax_groups" ("company_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_accounts" ("currency_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_accounts" ("creator_id");

ALTER TABLE "database.sqlite"."countries" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_tags" ("country_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_tags" ("creator_id");

ALTER TABLE "database.sqlite"."accounts_tax_groups" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_taxes" ("tax_group_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_taxes" ("creator_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_taxes" ("cash_basis_transition_account_id");

ALTER TABLE "database.sqlite"."countries" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_taxes" ("country_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_taxes" ("company_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_tax_partition_lines" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_tax_partition_lines" ("creator_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_tax_partition_lines" ("account_id");

ALTER TABLE "database.sqlite"."accounts_taxes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_tax_partition_lines" ("tax_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_journals" ("suspense_account_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_journals" ("currency_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_journals" ("default_account_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_journals" ("loss_account_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_journals" ("creator_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_journals" ("profit_account_id");

ALTER TABLE "database.sqlite"."banks" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_journals" ("bank_account_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_journals" ("company_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_journal_accounts" ("account_id");

ALTER TABLE "database.sqlite"."accounts_journals" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_journal_accounts" ("journal_id");

ALTER TABLE "database.sqlite"."accounts_taxes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_tax_taxes" ("child_tax_id");

ALTER TABLE "database.sqlite"."accounts_taxes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_tax_taxes" ("parent_tax_id");

ALTER TABLE "database.sqlite"."accounts_taxes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_taxes" ("tax_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_taxes" ("account_id");

ALTER TABLE "database.sqlite"."accounts_account_tags" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_account_tags" ("account_tag_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_account_tags" ("account_id");

ALTER TABLE "database.sqlite"."accounts_journals" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_journals" ("journal_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_journals" ("account_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_fiscal_positions" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_fiscal_positions" ("creator_id");

ALTER TABLE "database.sqlite"."countries" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_fiscal_positions" ("country_id");

ALTER TABLE "database.sqlite"."countries" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_fiscal_positions" ("country_group_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_fiscal_position_taxes" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_fiscal_position_taxes" ("company_id");

ALTER TABLE "database.sqlite"."accounts_taxes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_fiscal_position_taxes" ("tax_destination_id");

ALTER TABLE "database.sqlite"."accounts_fiscal_positions" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_fiscal_position_taxes" ("fiscal_position_id");

ALTER TABLE "database.sqlite"."accounts_taxes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_fiscal_position_taxes" ("tax_source_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_cash_roundings" ("loss_account_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_cash_roundings" ("profit_account_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_cash_roundings" ("creator_id");

ALTER TABLE "database.sqlite"."accounts_taxes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_product_taxes" ("tax_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_product_taxes" ("product_id");

ALTER TABLE "database.sqlite"."accounts_taxes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_product_supplier_taxes" ("tax_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_product_supplier_taxes" ("product_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_reconciles" ("created_by");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_reconciles" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_methods" ("created_by");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_method_lines" ("creator_id");

ALTER TABLE "database.sqlite"."accounts_journals" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_method_lines" ("journal_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_method_lines" ("payment_account_id");

ALTER TABLE "database.sqlite"."accounts_payment_methods" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_method_lines" ("payment_method_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_bank_statements" ("created_by");

ALTER TABLE "database.sqlite"."accounts_journals" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_bank_statements" ("journal_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_bank_statements" ("company_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_bank_statement_lines" ("company_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_bank_statement_lines" ("currency_id");

ALTER TABLE "database.sqlite"."accounts_bank_statements" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_bank_statement_lines" ("statement_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_bank_statement_lines" ("created_by");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_bank_statement_lines" ("foreign_currency_id");

ALTER TABLE "database.sqlite"."accounts_account_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_bank_statement_lines" ("move_id");

ALTER TABLE "database.sqlite"."accounts_journals" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_bank_statement_lines" ("journal_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_bank_statement_lines" ("partner_id");

ALTER TABLE "database.sqlite"."accounts_account_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_full_reconciles" ("exchange_move_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_full_reconciles" ("created_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_partial_reconciles" ("created_by");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_partial_reconciles" ("debit_currency_id");

ALTER TABLE "database.sqlite"."accounts_account_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_partial_reconciles" ("debit_move_id");

ALTER TABLE "database.sqlite"."accounts_full_reconciles" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_partial_reconciles" ("full_reconcile_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_partial_reconciles" ("company_id");

ALTER TABLE "database.sqlite"."accounts_account_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_partial_reconciles" ("exchange_move_id");

ALTER TABLE "database.sqlite"."accounts_account_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_partial_reconciles" ("credit_move_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_partial_reconciles" ("credit_currency_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_registers" ("currency_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_registers" ("custom_user_currency_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_registers" ("creator_id");

ALTER TABLE "database.sqlite"."partners_bank_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_registers" ("partner_bank_id");

ALTER TABLE "database.sqlite"."accounts_journals" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_registers" ("journal_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_registers" ("company_id");

ALTER TABLE "database.sqlite"."accounts_payment_method_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_registers" ("payment_method_line_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_registers" ("source_currency_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_registers" ("writeoff_account_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_payment_registers" ("partner_id");

ALTER TABLE "database.sqlite"."accounts_account_move_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_payment_register_move_lines" ("move_line_id");

ALTER TABLE "database.sqlite"."accounts_payment_registers" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_payment_register_move_lines" ("payment_register_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_partners" ("creator_id");

ALTER TABLE "database.sqlite"."partners_titles" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_partners" ("title_id");

ALTER TABLE "database.sqlite"."countries" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_partners" ("country_id");

ALTER TABLE "database.sqlite"."accounts_payment_terms" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_partners" ("property_payment_term_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_partners" ("property_account_payable_id");

ALTER TABLE "database.sqlite"."accounts_payment_method_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_partners" ("property_inbound_payment_method_line_id");

ALTER TABLE "database.sqlite"."accounts_payment_method_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_partners" ("property_outbound_payment_method_line_id");

ALTER TABLE "database.sqlite"."accounts_payment_terms" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_partners" ("property_supplier_payment_term_id");

ALTER TABLE "database.sqlite"."partners_industries" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_partners" ("industry_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_partners" ("user_id");

ALTER TABLE "database.sqlite"."states" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_partners" ("state_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_partners" ("company_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_partners" ("property_account_receivable_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_partners" ("property_account_position_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."partners_partners" ("parent_id");

ALTER TABLE "database.sqlite"."accounts_taxes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_accounts_move_line_taxes" ("tax_id");

ALTER TABLE "database.sqlite"."accounts_account_move_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_accounts_move_line_taxes" ("move_line_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_accounts_move_reversals" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_accounts_move_reversals" ("company_id");

ALTER TABLE "database.sqlite"."accounts_account_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_accounts_move_reversal_move" ("move_id");

ALTER TABLE "database.sqlite"."accounts_accounts_move_reversals" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_accounts_move_reversal_move" ("reversal_id");

ALTER TABLE "database.sqlite"."accounts_account_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_accounts_move_reversal_new_move" ("new_move_id");

ALTER TABLE "database.sqlite"."accounts_accounts_move_reversals" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_accounts_move_reversal_new_move" ("reversal_id");

ALTER TABLE "database.sqlite"."accounts_account_payments" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_accounts_move_payment" ("payment_id");

ALTER TABLE "database.sqlite"."accounts_account_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_accounts_move_payment" ("invoice_id");

ALTER TABLE "database.sqlite"."accounts_partial_reconciles" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("tax_cash_basis_reconcile_id");

ALTER TABLE "database.sqlite"."accounts_account_payments" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("origin_payment_id");

ALTER TABLE "database.sqlite"."accounts_bank_statement_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("statement_line_id");

ALTER TABLE "database.sqlite"."accounts_account_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("auto_post_origin_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("partner_id");

ALTER TABLE "database.sqlite"."accounts_payment_method_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("preferred_payment_method_line_id");

ALTER TABLE "database.sqlite"."utm_campaigns" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("campaign_id");

ALTER TABLE "database.sqlite"."accounts_account_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("reversed_entry_id");

ALTER TABLE "database.sqlite"."accounts_payment_terms" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("invoice_payment_term_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("partner_shipping_id");

ALTER TABLE "database.sqlite"."utm_sources" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("source_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("company_id");

ALTER TABLE "database.sqlite"."partners_bank_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("partner_bank_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("currency_id");

ALTER TABLE "database.sqlite"."utm_mediums" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("medium_id");

ALTER TABLE "database.sqlite"."accounts_incoterms" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("invoice_incoterm_id");

ALTER TABLE "database.sqlite"."accounts_account_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("tax_cash_basis_origin_move_id");

ALTER TABLE "database.sqlite"."accounts_fiscal_positions" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("fiscal_position_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("invoice_user_id");

ALTER TABLE "database.sqlite"."accounts_cash_roundings" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("invoice_cash_rounding_id");

ALTER TABLE "database.sqlite"."accounts_journals" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("journal_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_moves" ("commercial_partner_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_work_locations" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_work_locations" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_categories" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employment_types" ("creator_id");

ALTER TABLE "database.sqlite"."countries" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employment_types" ("country_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_skill_types" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_skill_levels" ("creator_id");

ALTER TABLE "database.sqlite"."employees_skill_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_skill_levels" ("skill_type_id");

ALTER TABLE "database.sqlite"."employees_skill_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_skills" ("skill_type_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_skills" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_calendars" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_calendars" ("company_id");

ALTER TABLE "database.sqlite"."employees_calendars" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_calendar_attendances" ("calendar_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_calendar_attendances" ("creator_id");

ALTER TABLE "database.sqlite"."employees_calendars" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_calendar_leaves" ("calendar_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_calendar_leaves" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_calendar_leaves" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_departure_reasons" ("creator_id");

ALTER TABLE "database.sqlite"."countries" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("country_of_birth");

ALTER TABLE "database.sqlite"."employees_job_positions" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("job_id");

ALTER TABLE "database.sqlite"."employees_departure_reasons" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("departure_reason_id");

ALTER TABLE "database.sqlite"."employees_employees" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("parent_id");

ALTER TABLE "database.sqlite"."employees_departments" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("department_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("user_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("partner_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("attendance_manager_id");

ALTER TABLE "database.sqlite"."states" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("state_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("creator_id");

ALTER TABLE "database.sqlite"."countries" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("private_country_id");

ALTER TABLE "database.sqlite"."employees_calendars" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("calendar_id");

ALTER TABLE "database.sqlite"."employees_employees" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("coach_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("leave_manager_id");

ALTER TABLE "database.sqlite"."employees_work_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("work_location_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("company_id");

ALTER TABLE "database.sqlite"."countries" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("country_id");

ALTER TABLE "database.sqlite"."states" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("private_state_id");

ALTER TABLE "database.sqlite"."partners_bank_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employees" ("bank_account_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employee_skills" ("creator_id");

ALTER TABLE "database.sqlite"."employees_skills" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employee_skills" ("skill_id");

ALTER TABLE "database.sqlite"."employees_employees" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employee_skills" ("employee_id");

ALTER TABLE "database.sqlite"."employees_skill_levels" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employee_skills" ("skill_level_id");

ALTER TABLE "database.sqlite"."employees_skill_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employee_skills" ("skill_type_id");

ALTER TABLE "database.sqlite"."employees_categories" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employee_categories" ("category_id");

ALTER TABLE "database.sqlite"."employees_employees" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employee_categories" ("employee_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employee_resume_line_types" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employee_resumes" ("creator_id");

ALTER TABLE "database.sqlite"."employees_employees" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employee_resumes" ("employee_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employee_resumes" ("user_id");

ALTER TABLE "database.sqlite"."employees_employee_resume_line_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_employee_resumes" ("employee_resume_line_type_id");

ALTER TABLE "database.sqlite"."employees_employees" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_departments" ("manager_id");

ALTER TABLE "database.sqlite"."employees_departments" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_departments" ("master_department_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_departments" ("creator_id");

ALTER TABLE "database.sqlite"."employees_departments" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_departments" ("parent_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_departments" ("company_id");

ALTER TABLE "database.sqlite"."employees_skills" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."job_position_skills" ("skill_id");

ALTER TABLE "database.sqlite"."employees_job_positions" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."job_position_skills" ("job_position_id");

ALTER TABLE "database.sqlite"."employees_departments" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."activity_plans" ("department_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."activity_plans" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."activity_plans" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_tags" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_storage_categories" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_storage_categories" ("company_id");

ALTER TABLE "database.sqlite"."inventories_warehouses" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_locations" ("warehouse_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_locations" ("creator_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_locations" ("parent_id");

ALTER TABLE "database.sqlite"."inventories_storage_categories" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_locations" ("storage_category_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_locations" ("company_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_operation_types" ("company_id");

ALTER TABLE "database.sqlite"."inventories_warehouses" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_operation_types" ("warehouse_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_operation_types" ("source_location_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_operation_types" ("creator_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_operation_types" ("destination_location_id");

ALTER TABLE "database.sqlite"."inventories_operation_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_operation_types" ("return_operation_type_id");

ALTER TABLE "database.sqlite"."inventories_warehouses" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_routes" ("supplier_warehouse_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_routes" ("creator_id");

ALTER TABLE "database.sqlite"."inventories_warehouses" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_routes" ("supplied_warehouse_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_routes" ("company_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_rules" ("partner_address_id");

ALTER TABLE "database.sqlite"."inventories_routes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_rules" ("route_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_rules" ("company_id");

ALTER TABLE "database.sqlite"."inventories_operation_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_rules" ("operation_type_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_rules" ("source_location_id");

ALTER TABLE "database.sqlite"."inventories_warehouses" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_rules" ("warehouse_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_rules" ("creator_id");

ALTER TABLE "database.sqlite"."inventories_warehouses" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_rules" ("propagate_warehouse_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_rules" ("destination_location_id");

ALTER TABLE "database.sqlite"."inventories_warehouses" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_route_warehouses" ("warehouse_id");

ALTER TABLE "database.sqlite"."inventories_routes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_route_warehouses" ("route_id");

ALTER TABLE "database.sqlite"."inventories_operation_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("store_type_id");

ALTER TABLE "database.sqlite"."inventories_operation_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("pick_type_id");

ALTER TABLE "database.sqlite"."inventories_routes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("delivery_route_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("pack_stock_location_id");

ALTER TABLE "database.sqlite"."inventories_operation_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("internal_type_id");

ALTER TABLE "database.sqlite"."inventories_operation_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("out_type_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("view_location_id");

ALTER TABLE "database.sqlite"."inventories_routes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("crossdock_route_id");

ALTER TABLE "database.sqlite"."inventories_operation_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("in_type_id");

ALTER TABLE "database.sqlite"."inventories_operation_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("qc_type_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("creator_id");

ALTER TABLE "database.sqlite"."inventories_rules" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("mto_pull_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("input_stock_location_id");

ALTER TABLE "database.sqlite"."inventories_rules" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("buy_pull_id");

ALTER TABLE "database.sqlite"."inventories_operation_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("pack_type_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("company_id");

ALTER TABLE "database.sqlite"."inventories_routes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("reception_route_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("output_stock_location_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("qc_stock_location_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("lot_stock_location_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("partner_address_id");

ALTER TABLE "database.sqlite"."inventories_operation_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouses" ("xdock_type_id");

ALTER TABLE "database.sqlite"."inventories_warehouses" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouse_resupplies" ("supplier_warehouse_id");

ALTER TABLE "database.sqlite"."inventories_warehouses" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_warehouse_resupplies" ("supplied_warehouse_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_package_types" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_package_types" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_packages" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_packages" ("company_id");

ALTER TABLE "database.sqlite"."inventories_package_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_packages" ("package_type_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_packages" ("location_id");

ALTER TABLE "database.sqlite"."products_categories" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_category_routes" ("category_id");

ALTER TABLE "database.sqlite"."inventories_routes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_category_routes" ("route_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_product_routes" ("product_id");

ALTER TABLE "database.sqlite"."inventories_routes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_product_routes" ("route_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_packagings" ("company_id");

ALTER TABLE "database.sqlite"."inventories_package_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_packagings" ("package_type_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_packagings" ("product_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_packagings" ("creator_id");

ALTER TABLE "database.sqlite"."inventories_storage_categories" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_storage_category_capacities" ("storage_category_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_storage_category_capacities" ("creator_id");

ALTER TABLE "database.sqlite"."inventories_package_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_storage_category_capacities" ("package_type_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_storage_category_capacities" ("product_id");

ALTER TABLE "database.sqlite"."inventories_routes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_route_packagings" ("route_id");

ALTER TABLE "database.sqlite"."products_packagings" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_route_packagings" ("packaging_id");

ALTER TABLE "database.sqlite"."unit_of_measures" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_lots" ("uom_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_lots" ("product_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_lots" ("location_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_lots" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_lots" ("company_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_product_quantities" ("location_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_product_quantities" ("partner_id");

ALTER TABLE "database.sqlite"."inventories_packages" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_product_quantities" ("package_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_product_quantities" ("user_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_product_quantities" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_product_quantities" ("creator_id");

ALTER TABLE "database.sqlite"."inventories_storage_categories" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_product_quantities" ("storage_category_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_product_quantities" ("product_id");

ALTER TABLE "database.sqlite"."inventories_lots" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_product_quantities" ("lot_id");

ALTER TABLE "database.sqlite"."inventories_packages" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_product_quantity_relocations" ("destination_package_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_product_quantity_relocations" ("creator_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_product_quantity_relocations" ("destination_location_id");

ALTER TABLE "database.sqlite"."inventories_operations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_package_levels" ("operation_id");

ALTER TABLE "database.sqlite"."inventories_packages" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_package_levels" ("package_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_package_levels" ("destination_location_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_package_levels" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_package_levels" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_package_destinations" ("creator_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_package_destinations" ("destination_location_id");

ALTER TABLE "database.sqlite"."inventories_operations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_package_destinations" ("operation_id");

ALTER TABLE "database.sqlite"."unit_of_measures" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_scraps" ("uom_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_scraps" ("product_id");

ALTER TABLE "database.sqlite"."inventories_operations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_scraps" ("operation_id");

ALTER TABLE "database.sqlite"."inventories_lots" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_scraps" ("lot_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_scraps" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_scraps" ("company_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_scraps" ("destination_location_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_scraps" ("source_location_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_scraps" ("partner_id");

ALTER TABLE "database.sqlite"."inventories_packages" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_scraps" ("package_id");

ALTER TABLE "database.sqlite"."inventories_tags" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_scrap_tags" ("tag_id");

ALTER TABLE "database.sqlite"."inventories_scraps" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_scrap_tags" ("scrap_id");

ALTER TABLE "database.sqlite"."inventories_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_move_destinations" ("destination_move_id");

ALTER TABLE "database.sqlite"."inventories_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_move_destinations" ("origin_move_id");

ALTER TABLE "database.sqlite"."unit_of_measures" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_move_lines" ("uom_id");

ALTER TABLE "database.sqlite"."inventories_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_move_lines" ("move_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_move_lines" ("creator_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_move_lines" ("destination_location_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_move_lines" ("source_location_id");

ALTER TABLE "database.sqlite"."inventories_operations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_move_lines" ("operation_id");

ALTER TABLE "database.sqlite"."inventories_lots" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_move_lines" ("lot_id");

ALTER TABLE "database.sqlite"."inventories_package_levels" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_move_lines" ("package_level_id");

ALTER TABLE "database.sqlite"."inventories_packages" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_move_lines" ("package_id");

ALTER TABLE "database.sqlite"."inventories_packages" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_move_lines" ("result_package_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_move_lines" ("product_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_move_lines" ("partner_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_move_lines" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_order_points" ("creator_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_order_points" ("location_id");

ALTER TABLE "database.sqlite"."inventories_routes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_order_points" ("route_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_order_points" ("product_id");

ALTER TABLE "database.sqlite"."inventories_warehouses" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_order_points" ("warehouse_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_order_points" ("company_id");

ALTER TABLE "database.sqlite"."products_categories" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_order_points" ("product_category_id");

ALTER TABLE "database.sqlite"."products_categories" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_products" ("category_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_products" ("property_account_income_id");

ALTER TABLE "database.sqlite"."unit_of_measures" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_products" ("uom_po_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_products" ("property_account_expense_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_products" ("company_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_products" ("parent_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_products" ("responsible_id");

ALTER TABLE "database.sqlite"."unit_of_measures" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_products" ("uom_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."products_products" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."payments_payment_methods" ("created_by");

ALTER TABLE "database.sqlite"."payments_payment_methods" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."payments_payment_methods" ("primary_payment_method_id");

ALTER TABLE "database.sqlite"."payments_payment_methods" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."payments_payment_tokens" ("payment_method_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."payments_payment_tokens" ("partner_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."payments_payment_tokens" ("created_by");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."payments_payment_tokens" ("company_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."payments_payment_transactions" ("currency_id");

ALTER TABLE "database.sqlite"."accounts_journals" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."payments_payment_transactions" ("journal_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."payments_payment_transactions" ("created_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."payments_payment_transactions" ("company_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."payments_payment_transactions" ("foreign_currency_id");

ALTER TABLE "database.sqlite"."accounts_account_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."payments_payment_transactions" ("move_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."payments_payment_transactions" ("partner_id");

ALTER TABLE "database.sqlite"."accounts_bank_statements" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."payments_payment_transactions" ("statement_id");

ALTER TABLE "database.sqlite"."partners_bank_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_payments" ("partner_bank_id");

ALTER TABLE "database.sqlite"."accounts_account_payments" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_payments" ("paired_internal_transfer_payment_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_payments" ("created_by");

ALTER TABLE "database.sqlite"."accounts_payment_method_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_payments" ("payment_method_line_id");

ALTER TABLE "database.sqlite"."payments_payment_transactions" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_payments" ("payment_transaction_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_payments" ("destination_account_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_payments" ("company_id");

ALTER TABLE "database.sqlite"."accounts_journals" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_payments" ("journal_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_payments" ("currency_id");

ALTER TABLE "database.sqlite"."accounts_account_payments" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_payments" ("source_payment_id");

ALTER TABLE "database.sqlite"."payments_payment_tokens" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_payments" ("payment_token_id");

ALTER TABLE "database.sqlite"."accounts_account_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_payments" ("move_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_payments" ("partner_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_payments" ("outstanding_account_id");

ALTER TABLE "database.sqlite"."accounts_payment_methods" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_payments" ("payment_method_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_project_stages" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_project_stages" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_projects" ("user_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_projects" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_projects" ("company_id");

ALTER TABLE "database.sqlite"."projects_project_stages" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_projects" ("stage_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_projects" ("partner_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_milestones" ("creator_id");

ALTER TABLE "database.sqlite"."projects_projects" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_milestones" ("project_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_user_project_favorites" ("user_id");

ALTER TABLE "database.sqlite"."projects_projects" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_user_project_favorites" ("project_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_tags" ("creator_id");

ALTER TABLE "database.sqlite"."projects_tags" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_project_tag" ("tag_id");

ALTER TABLE "database.sqlite"."projects_projects" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_project_tag" ("project_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_task_stages" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_task_stages" ("user_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_task_stages" ("company_id");

ALTER TABLE "database.sqlite"."projects_projects" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_task_stages" ("project_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_tasks" ("partner_id");

ALTER TABLE "database.sqlite"."projects_tasks" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_tasks" ("parent_id");

ALTER TABLE "database.sqlite"."projects_task_stages" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_tasks" ("stage_id");

ALTER TABLE "database.sqlite"."projects_projects" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_tasks" ("project_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_tasks" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_tasks" ("creator_id");

ALTER TABLE "database.sqlite"."projects_milestones" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_tasks" ("milestone_id");

ALTER TABLE "database.sqlite"."projects_task_stages" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_task_users" ("stage_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_task_users" ("user_id");

ALTER TABLE "database.sqlite"."projects_tasks" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_task_users" ("task_id");

ALTER TABLE "database.sqlite"."projects_tags" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_task_tag" ("tag_id");

ALTER TABLE "database.sqlite"."projects_tasks" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."projects_task_tag" ("task_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."analytic_records" ("partner_id");

ALTER TABLE "database.sqlite"."projects_tasks" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."analytic_records" ("task_id");

ALTER TABLE "database.sqlite"."projects_projects" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."analytic_records" ("project_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."analytic_records" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."analytic_records" ("user_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."analytic_records" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_order_groups" ("creator_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_requisitions" ("partner_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_requisitions" ("currency_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_requisitions" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_requisitions" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_requisitions" ("user_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_requisition_lines" ("product_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_requisition_lines" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_requisition_lines" ("creator_id");

ALTER TABLE "database.sqlite"."purchases_requisitions" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_requisition_lines" ("requisition_id");

ALTER TABLE "database.sqlite"."unit_of_measures" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_requisition_lines" ("uom_id");

ALTER TABLE "database.sqlite"."purchases_order_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_order_line_taxes" ("order_line_id");

ALTER TABLE "database.sqlite"."accounts_taxes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_order_line_taxes" ("tax_id");

ALTER TABLE "database.sqlite"."accounts_account_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_order_account_moves" ("move_id");

ALTER TABLE "database.sqlite"."purchases_orders" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_order_account_moves" ("order_id");

ALTER TABLE "database.sqlite"."purchases_order_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("purchase_order_line_id");

ALTER TABLE "database.sqlite"."accounts_account_payments" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("payment_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("company_currency_id");

ALTER TABLE "database.sqlite"."accounts_tax_partition_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("tax_repartition_line_id");

ALTER TABLE "database.sqlite"."accounts_taxes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("group_tax_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("currency_id");

ALTER TABLE "database.sqlite"."accounts_tax_groups" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("tax_group_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("company_id");

ALTER TABLE "database.sqlite"."accounts_bank_statement_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("statement_line_id");

ALTER TABLE "database.sqlite"."accounts_accounts" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("account_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("partner_id");

ALTER TABLE "database.sqlite"."unit_of_measures" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("uom_id");

ALTER TABLE "database.sqlite"."accounts_taxes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("tax_line_id");

ALTER TABLE "database.sqlite"."accounts_journals" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("journal_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("created_by");

ALTER TABLE "database.sqlite"."accounts_bank_statements" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("statement_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("product_id");

ALTER TABLE "database.sqlite"."accounts_full_reconciles" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("full_reconcile_id");

ALTER TABLE "database.sqlite"."accounts_account_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("move_id");

ALTER TABLE "database.sqlite"."accounts_reconciles" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."accounts_account_move_lines" ("reconcile_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_orders" ("currency_id");

ALTER TABLE "database.sqlite"."purchases_order_groups" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_orders" ("purchases_group_id");

ALTER TABLE "database.sqlite"."accounts_incoterms" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_orders" ("incoterm_id");

ALTER TABLE "database.sqlite"."accounts_fiscal_positions" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_orders" ("fiscal_position_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_orders" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_orders" ("company_id");

ALTER TABLE "database.sqlite"."purchases_requisitions" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_orders" ("requisition_id");

ALTER TABLE "database.sqlite"."accounts_payment_terms" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_orders" ("payment_term_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_orders" ("user_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_orders" ("partner_id");

ALTER TABLE "database.sqlite"."inventories_operation_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_orders" ("operation_type_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_order_lines" ("creator_id");

ALTER TABLE "database.sqlite"."inventories_order_points" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_order_lines" ("order_point_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_order_lines" ("currency_id");

ALTER TABLE "database.sqlite"."products_packagings" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_order_lines" ("product_packaging_id");

ALTER TABLE "database.sqlite"."unit_of_measures" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_order_lines" ("uom_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_order_lines" ("company_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_order_lines" ("partner_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_order_lines" ("final_location_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_order_lines" ("product_id");

ALTER TABLE "database.sqlite"."purchases_orders" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_order_lines" ("order_id");

ALTER TABLE "database.sqlite"."inventories_operations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_order_operations" ("inventory_operation_id");

ALTER TABLE "database.sqlite"."purchases_orders" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."purchases_order_operations" ("purchase_order_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_stages" ("creator_id");

ALTER TABLE "database.sqlite"."recruitments_stages" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_stages_jobs" ("stage_id");

ALTER TABLE "database.sqlite"."employees_job_positions" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_stages_jobs" ("job_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_degrees" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_refuse_reasons" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_applicant_categories" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_candidates" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_candidates" ("creator_id");

ALTER TABLE "database.sqlite"."employees_employees" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_candidates" ("employee_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_candidates" ("manager_id");

ALTER TABLE "database.sqlite"."recruitments_degrees" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_candidates" ("degree_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_candidates" ("partner_id");

ALTER TABLE "database.sqlite"."recruitments_applicant_categories" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_candidate_applicant_categories" ("category_id");

ALTER TABLE "database.sqlite"."recruitments_candidates" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_candidate_applicant_categories" ("candidate_id");

ALTER TABLE "database.sqlite"."recruitments_candidates" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_candidate_skills" ("candidate_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_candidate_skills" ("creator_id");

ALTER TABLE "database.sqlite"."employees_skills" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_candidate_skills" ("skill_id");

ALTER TABLE "database.sqlite"."employees_skill_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_candidate_skills" ("skill_type_id");

ALTER TABLE "database.sqlite"."employees_skill_levels" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_candidate_skills" ("skill_level_id");

ALTER TABLE "database.sqlite"."recruitments_stages" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_applicants" ("stage_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_applicants" ("recruiter_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_applicants" ("creator_id");

ALTER TABLE "database.sqlite"."recruitments_stages" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_applicants" ("last_stage_id");

ALTER TABLE "database.sqlite"."recruitments_candidates" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_applicants" ("candidate_id");

ALTER TABLE "database.sqlite"."utm_mediums" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_applicants" ("medium_id");

ALTER TABLE "database.sqlite"."employees_departments" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_applicants" ("department_id");

ALTER TABLE "database.sqlite"."utm_sources" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_applicants" ("source_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_applicants" ("company_id");

ALTER TABLE "database.sqlite"."recruitments_refuse_reasons" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_applicants" ("refuse_reason_id");

ALTER TABLE "database.sqlite"."employees_job_positions" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_applicants" ("job_id");

ALTER TABLE "database.sqlite"."recruitments_applicants" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_applicant_interviewers" ("applicant_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_applicant_interviewers" ("interviewer_id");

ALTER TABLE "database.sqlite"."recruitments_applicants" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_applicant_applicant_categories" ("applicant_id");

ALTER TABLE "database.sqlite"."recruitments_applicant_categories" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_applicant_applicant_categories" ("category_id");

ALTER TABLE "database.sqlite"."employees_employment_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_job_positions" ("employment_type_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_job_positions" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_job_positions" ("recruiter_id");

ALTER TABLE "database.sqlite"."employees_departments" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_job_positions" ("department_id");

ALTER TABLE "database.sqlite"."partners_industries" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_job_positions" ("industry_id");

ALTER TABLE "database.sqlite"."employees_employees" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_job_positions" ("manager_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_job_positions" ("company_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."employees_job_positions" ("address_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_job_position_interviewers" ("user_id");

ALTER TABLE "database.sqlite"."employees_job_positions" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."recruitments_job_position_interviewers" ("job_position_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_teams" ("creator_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_teams" ("user_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_teams" ("company_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_team_members" ("user_id");

ALTER TABLE "database.sqlite"."sales_teams" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_team_members" ("team_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_templates" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_templates" ("company_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_template_products" ("product_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_template_products" ("creator_id");

ALTER TABLE "database.sqlite"."unit_of_measures" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_template_products" ("product_uom_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_template_products" ("company_id");

ALTER TABLE "database.sqlite"."sales_order_templates" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_template_products" ("order_template_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_options" ("creator_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_options" ("product_id");

ALTER TABLE "database.sqlite"."unit_of_measures" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_options" ("uom_id");

ALTER TABLE "database.sqlite"."sales_orders" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_options" ("order_id");

ALTER TABLE "database.sqlite"."sales_order_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_options" ("line_id");

ALTER TABLE "database.sqlite"."accounts_taxes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_line_taxes" ("tax_id");

ALTER TABLE "database.sqlite"."sales_order_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_line_taxes" ("order_line_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_tags" ("creator_id");

ALTER TABLE "database.sqlite"."accounts_account_move_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_line_invoices" ("invoice_line_id");

ALTER TABLE "database.sqlite"."sales_order_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_line_invoices" ("order_line_id");

ALTER TABLE "database.sqlite"."sales_tags" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_tags" ("tag_id");

ALTER TABLE "database.sqlite"."sales_orders" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_tags" ("order_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_advance_payment_invoices" ("creator_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_advance_payment_invoices" ("company_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_advance_payment_invoices" ("currency_id");

ALTER TABLE "database.sqlite"."sales_orders" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_advance_payment_invoice_order_sales" ("order_id");

ALTER TABLE "database.sqlite"."sales_advance_payment_invoices" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_advance_payment_invoice_order_sales" ("advance_payment_invoice_id");

ALTER TABLE "database.sqlite"."accounts_account_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_invoices" ("move_id");

ALTER TABLE "database.sqlite"."sales_orders" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_invoices" ("order_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_operations" ("owner_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_operations" ("partner_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_operations" ("company_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_operations" ("source_location_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_operations" ("destination_location_id");

ALTER TABLE "database.sqlite"."sales_orders" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_operations" ("sale_order_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_operations" ("creator_id");

ALTER TABLE "database.sqlite"."inventories_operation_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_operations" ("operation_type_id");

ALTER TABLE "database.sqlite"."inventories_operations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_operations" ("return_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_operations" ("user_id");

ALTER TABLE "database.sqlite"."inventories_operations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_operations" ("back_order_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("creator_id");

ALTER TABLE "database.sqlite"."inventories_operations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("operation_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("source_location_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("destination_location_id");

ALTER TABLE "database.sqlite"."inventories_moves" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("origin_returned_move_id");

ALTER TABLE "database.sqlite"."inventories_rules" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("rule_id");

ALTER TABLE "database.sqlite"."purchases_order_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("purchase_order_line_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("restrict_partner_id");

ALTER TABLE "database.sqlite"."unit_of_measures" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("uom_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("product_id");

ALTER TABLE "database.sqlite"."inventories_package_levels" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("package_level_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("company_id");

ALTER TABLE "database.sqlite"."inventories_locations" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("final_location_id");

ALTER TABLE "database.sqlite"."inventories_operation_types" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("operation_type_id");

ALTER TABLE "database.sqlite"."sales_order_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("sale_order_line_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("partner_id");

ALTER TABLE "database.sqlite"."inventories_warehouses" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("warehouse_id");

ALTER TABLE "database.sqlite"."inventories_scraps" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("scrap_id");

ALTER TABLE "database.sqlite"."products_packagings" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."inventories_moves" ("product_packaging_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_orders" ("partner_invoice_id");

ALTER TABLE "database.sqlite"."sales_order_templates" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_orders" ("sale_order_template_id");

ALTER TABLE "database.sqlite"."utm_sources" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_orders" ("utm_source_id");

ALTER TABLE "database.sqlite"."accounts_payment_terms" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_orders" ("payment_term_id");

ALTER TABLE "database.sqlite"."accounts_fiscal_positions" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_orders" ("fiscal_position_id");

ALTER TABLE "database.sqlite"."utm_mediums" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_orders" ("medium_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_orders" ("user_id");

ALTER TABLE "database.sqlite"."sales_teams" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_orders" ("team_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_orders" ("creator_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_orders" ("partner_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_orders" ("company_id");

ALTER TABLE "database.sqlite"."utm_campaigns" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_orders" ("campaign_id");

ALTER TABLE "database.sqlite"."inventories_warehouses" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_orders" ("warehouse_id");

ALTER TABLE "database.sqlite"."accounts_journals" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_orders" ("journal_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_orders" ("partner_shipping_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_orders" ("currency_id");

ALTER TABLE "database.sqlite"."partners_partners" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_lines" ("order_partner_id");

ALTER TABLE "database.sqlite"."products_packagings" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_lines" ("product_packaging_id");

ALTER TABLE "database.sqlite"."companies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_lines" ("company_id");

ALTER TABLE "database.sqlite"."unit_of_measures" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_lines" ("product_uom_id");

ALTER TABLE "database.sqlite"."inventories_warehouses" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_lines" ("warehouse_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_lines" ("creator_id");

ALTER TABLE "database.sqlite"."currencies" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_lines" ("currency_id");

ALTER TABLE "database.sqlite"."sales_order_lines" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_lines" ("linked_sale_order_sale_id");

ALTER TABLE "database.sqlite"."sales_orders" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_lines" ("order_id");

ALTER TABLE "database.sqlite"."products_products" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_lines" ("product_id");

ALTER TABLE "database.sqlite"."inventories_routes" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_lines" ("route_id");

ALTER TABLE "database.sqlite"."users" ADD FOREIGN KEY ("id") REFERENCES "database.sqlite"."sales_order_lines" ("salesman_id");
