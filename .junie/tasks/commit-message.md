# Testing Framework Improvement: Expanding Test Coverage

This commit continues our testing framework improvement initiative, focusing on expanding test coverage for the AureusERP plugins.

## Changes Made

- Created a comprehensive document identifying and prioritizing all plugins for test implementation
- Implemented unit tests for the Attribute model in the Invoices plugin
- Implemented unit tests for the BankAccount model in the Invoices plugin
- Implemented unit tests for the Category model in the Invoices plugin
- Implemented unit tests for the CreditNote model in the Invoices plugin
- Implemented unit tests for the Incoterm model in the Invoices plugin
- Updated task tracking documentation to reflect progress

## Technical Details

The new tests verify:
- Proper inheritance from base classes
- Correct attribute values and relationships
- Proper implementation of traits and interfaces
- Boot methods functionality (for BankAccount)
- Category hierarchy and full_name generation (for Category)
- Sequence prefix generation (for CreditNote)

The BankAccount test specifically verifies that the account_holder_name is properly set and updated based on the partner's name, as implemented in the model's boot methods.

The Category test includes verification of the category hierarchy functionality, ensuring that parent-child relationships are correctly established and that the full_name is properly generated based on the hierarchy.

The CreditNote test verifies the sequence prefix generation functionality, ensuring that the correct prefix is generated based on the move_type (OUT_REFUND generates 'RINV/' prefix).

The Incoterm test verifies the inheritance from the base Account Incoterm class, ensuring that all attributes and relationships are properly inherited and that the model uses the SoftDeletes trait.

## Next Steps

Continue implementing unit tests for the remaining models in the Invoices plugin, followed by other plugins according to the prioritization document.
