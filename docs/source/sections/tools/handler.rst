ErrorHandler and ProductionExceptionHandler
-------------------------------------------

TYPO3 allows to change handlers for Errors and Exceptions. Configure the following handlers and set up your email address to receive mails about these events:

#. Go to *Install Tool*, switch to *All configurations*
#. Set *[SYS][errorHandler]=\\Xima\\XmTools\\Typo3\\Handler\\ErrorHandler*
#. Set *[SYS][productionExceptionHandler]=\\Xima\\XmTools\\Typo3\\Handler\\ProductionExceptionHandler*
#. Set the recipient e-mail address in *TypoScript* (xmTools.errorHandler.recipient = ) or in *Constant Editor* (Multiple mail addresses possible as CSV)
