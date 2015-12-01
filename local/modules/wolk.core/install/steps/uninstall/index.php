<?php

if (
       !$this->UnInstallEvents()
    || !$this->UnInstallAgents()
    || !$this->UnInstallFiles()
    || !$this->UnInstallDB()
	|| !$this->UninstallMessageTemplates()
	|| !$this->UninstallRewrites()
) {
    return;
}

UnRegisterModule($this->MODULE_ID);