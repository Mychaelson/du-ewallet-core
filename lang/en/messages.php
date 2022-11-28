<?php

return [
    'field-required' => 'The :field field is required.',
    'field-number' => 'The :field must be a number.', 
    'field-digit' => 'The :field must be :number digits.', 
    'sms-sent' => 'We\'ve sent an otp code to :phone',
    'otp-invalid' => 'Invalid OTP',
    'otp-expired' => 'Expired OTP',
    'login-success' => 'Login Success',
    'old-password-invalid' => 'Invalid old password',
    'password-updated' => 'Password has been updated',
    'token-revoked' => 'Access token(s) has been revoked',
    'user-updated' => 'User data has been updated',
    'invalid-field' => 'Invalid field :field',
    'duplicate-email' => 'Email :email has been used',
    'duplicate-nickname' => 'Nickname :nickname has been used',
    'user-suspended' => 'Your account has been suspended',
    'pin-confirmed' => 'Pin confirmation success',
    'pin-confirmation-failed' => 'Incorrect PIN, you have :chance more chance(s)',
    'pin-confirmation-penalty' => 'Your account has reached the pin failure limit. Your account is now suspended for :penalty minutes',
    'question-id-invalid' => 'Invalid question id',
    'answer-invalid' => 'Invalid answer',
    'password-invalid' => 'Invalid password',
    'confirm-code-invalid' => 'Invalid confirm code',
    'duplicate-phone-number' => 'Phone number :username has been registered',
    'phone-change-invalid' => 'Invalid phone change request',
    'phone-change-completed' => 'Phone number change to :phone has been completed',
    'security-questions-updated' => 'Security question(s) has been updated',
    'close-account-submitted' => 'Close account request for :username has been submitted',
    'close-account-meta-submitted' => 'Close account meta for :username has been submitted',
    'close-account-not-submitted' => 'Close account request for :username has not been submitted',
    'close-account-not-completed' => 'Close account request for :username has not been completed',
    'account-closed' => 'Account :username has been closed',
    'logout-success' => 'Logout success',
    'contacts-added' => 'Contact(s) has been added',
    'virtual-accounts-added' => 'Virtual accounts have been added',
    'password-registered' => 'Password has been registered',
    'verification-mail-sent' => 'We\'ve sent a verification email to :mail',
    'email-verified' => 'Email :mail has been verified',
    'user-not-found' => 'User is not available nor active',
    'referral-code-invalid' => 'Referral code invalid',
    'referral-success' => 'Referral has been successfully added',
    'referrer-found' => 'Referral list found',
    'jobs-found' => 'Jobs found',
    'job-added' => 'Job successfully added',
    'user-found' => 'User found',
    'address-added' => 'Address has been successfully added',
    'last-pin-change-found' => 'Last Password change on :date',
    'pin-unlocked' => 'Password is not locked',
    'pin-locked' => 'Password is locked',
    'wallet' => [
        'transfer' => [
            'label-not-found' => 'Label not found.',
            'premium-only' => 'Sorry, you are not premium user.',
            'destination-not-found' => 'Your transfer destination is not found.',
            'self-transfer' => 'Your can\'t transfer to yourself.',
            'lock-tf' => 'Your account is locked to transfer funds.',
            'lock-out' => 'Your account is locked for outgoing funds.',
            'lock-in' => 'The destination account wallet is locked to receive incoming funds.',
            'insufficient-balance' => 'Your balance is not sufficient to make this transaction.',
            'min-transfer' => 'The transfer amount is too small. Minimum transfer is :min',
            'max-transfer' => 'The transfer amount is too big. Maximal transfer is :max',
            'daily-limit' => 'Reach your maximum daily transfer limit. Your remaining daily limit is :limit',
        ],
        'withdraw' => [
            'label-not-found' => 'Label not found.',
            'lock-wd' => 'Your account is locked to withdraw funds.',
            'insufficient-balance' => 'Your balance is not sufficient to make this transaction.',
            'min-transfer' => 'The withdraw amount is too small. Minimum withdraw is :min',
            'max-transfer' => 'The withdraw amount is too big. Maximal withdraw is :max',
            'daily-limit' => 'Reach your maximum daily withdraw limit. Your remaining daily limit is :limit',
            'exceeded-group-member' => 'Group limit member is exceeded',
        ],
    ],
    'unfinished-topup' => 'Other topup is on progress',
    'bank-invalid' => 'Destination bank not found',
    'label-invalid' => 'Label is not valid',
    'topup-limit-exceeded' => 'topup limit exceeded the daily limit',
    'pin-unlocked' => 'password is not locked',
    'pin-locked' => 'password is locked',
    'watch-status-updated' => 'User watch status updated due to multiples login',
    'topup-limit-exceeded' => 'Topup limit exceeded the daily limit',
    'topup-created' => 'Topup bill successfully created',
    'topup-cancelled' => 'Topup has been successfully cancelled',
    'topup-cancel-failed' => 'Topup cancellation failed',
    'bill-already-paid'=> 'Bill already paid',
    'transaction-not-found' => 'Transaction not found',
    'get-topup-transaction' => 'Topup transaction bill found',
    'instruction-not-found' => 'Bank instruction not found',
    'instruction-found' => 'Bank Instruction found',
    'transaction-found' => 'List Transactions found',
    'balance-limit-exceeded' => 'Wallet Balance limit exceeded the limit',
    'wallet-not-found' => 'Wallet not found',
    'invalid-value' => ':data value is not valid',
    'insufficient-ncash-balance' => 'Insufficient NCash balance to do this transaction',
    'topup-success' => 'Your top up of :amount is successful',
    'product-not-found' => 'Product with code :code not found',
    'inquiry-success' => 'Inquiry for :product success, proceed to payment',
    'payment-method-found' => 'List payment method found',
    'insufficient-wallet-balance' => 'Insufficient wallet balance to do this transaction',
    'transaction-expired' => 'Transaction Expired'
];
