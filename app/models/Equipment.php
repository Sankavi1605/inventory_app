<?php

// Legacy placeholder retained for backward compatibility with the old loader.
// Application code should now use `EquipmentModel` instead of `Equipment`.
// Keeping this file free of class declarations avoids name collisions with
// the `Equipment` controller while still allowing older includes to succeed.

trigger_error(
    'The Equipment model has been renamed to EquipmentModel. Update any calls to model("Equipment") accordingly.',
    E_USER_DEPRECATED
);