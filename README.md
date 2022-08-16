# Faq

Favicode folder must be put into app/code part of the project structure.

Module can be enabled with: bin/magento module:enable Favicode_Faq Favicode_FaqAdminNotification Favicode_FaqCustomerNotification, followed by bin/magento setup:upgrade <br>

Tables in database are called favicode_faq and favicode_faq_categories, their structure can be seen in etc/db_schema.xml 

If questions and categories are not visible in Admin panel under Content tab, enable them in Stores/Configuration, by selecting Customer Questions and Questions Categories under Favicode tab <br>

Please add one or more categories through Admin panel before submitting questions form product page
