# -*- coding: utf-8 -*-
import sys
from PyQt4 import QtCore, QtGui
from org.helioviewer.InstallWizard import Ui_InstallWizard
from org.helioviewer.jp2 import *
from org.helioviewer.db  import *
from org.helioviewer.utils import *

__INTRO_PAGE__ = 0
__DBADMIN_PAGE__ = 1
__HVDB_PAGE__ = 2
__JP2DIR_PAGE__ = 3
__INSTALL_PAGE__ = 4
__FINISH_PAGE__ = 5

#
# Main Application Window
#
# TODO (2009/08/21): Generate setup/Config.php and copy/move API files into proper location.
# TODO (2009/08/21): Add checks: 1. db exists, 2. no images found
#
class HelioviewerInstallWizard(QtGui.QWizard):

    def __init__(self, parent=None):
        QtGui.QWidget.__init__(self, parent)
        self.ui = Ui_InstallWizard()
        self.ui.setupUi(self)
        self.postSetup()
        self.initEvents()

    def postSetup(self):
        self.setPixmap(QtGui.QWizard.LogoPixmap, QtGui.QPixmap(":/Logos/color.png"))
        self.setupValidators()


    def setupValidators(self):
        # Mandatory fields
        self.ui.dbAdminPage.registerField("dbAdminUserName*", self.ui.dbAdminUserName)
        self.ui.dbAdminPage.registerField("dbAdminPassword*", self.ui.dbAdminPassword)
        self.ui.hvDatabaseSetupPage.registerField("hvUserName*", self.ui.hvUserName)
        self.ui.hvDatabaseSetupPage.registerField("hvPassword*", self.ui.hvPassword)

        alphanum = QtGui.QRegExpValidator(QtCore.QRegExp("[\w]*"), self)

        # DB Admin Info
        self.ui.dbAdminUserName.setValidator(alphanum)
        self.ui.dbAdminPassword.setValidator(alphanum)
        self.ui.hvUserName.setValidator(alphanum)
        self.ui.hvPassword.setValidator(alphanum)


    def initializePage(self, page):
        if page is __INSTALL_PAGE__:
            self.processImages()


    #def beginInstall(self):


    def validateCurrentPage(self):
        ''' Validates information for a given page '''
        page = self.currentId()

        print "Validating page %s" % str(page)

        # Database type & administrator information
        if page is __DBADMIN_PAGE__:
            canConnect = checkDBInfo(str(self.ui.dbAdminUserName.text()), str(self.ui.dbAdminPassword.text()), self.ui.mysqlRadioBtn.isChecked())
            if not canConnect:
                self.ui.dbAdminStatus.setText("<span style='color: red;'>Unable to connect to the database. Please check your login information and try again.</span>")
            else:
                self.ui.dbAdminStatus.clear()
            return canConnect

        # JP2 Archive location
        elif page is __JP2DIR_PAGE__:
            pathExists = checkPath(self.ui.jp2RootDirInput.text())
            if not pathExists:
                self.ui.jp2ArchiveStatus.setText("<span style='color: red;'>Not a valid location. Please check the filepath and permissions and try again.</span>")
            else:
                self.ui.jp2ArchiveStatus.clear()
            return pathExists

        # No validation required
        else:
            return True

    def processImages(self):
        ''' Process JPEG 2000 archive and enter information into the database '''
        admin, adminpass, hvuser, hvpass, jp2dir, mysql = self.getFormFields()

        print "Traversing JP2 Archive..."

        images = traverseDirectory(jp2dir)

        if(len(images) == 0):
            print "No JPEG 2000 images found. Exiting installation."
            sys.exit(2)
        else:
            self.ui.installProgress.setMaximum(len(images))
            print "Found " + str(len(images)) + " JPEG2000 images."

        print "Setting up DB Schema..."

        cursor = setupDatabaseSchema(admin, adminpass, hvuser, hvpass, mysql)

        print "Processing Images..."

        processJPEG2000Images(images, cursor, mysql)

    def getFormFields(self):
        ''' Grab form information '''
        mysql = self.ui.mysqlRadioBtn.isChecked()
        admin = str(self.ui.dbAdminUserName.text())
        adminpass = str(self.ui.dbAdminPassword.text())
        hvuser = str(self.ui.hvUserName.text())
        hvpass = str(self.ui.hvPassword.text())
        jp2dir = str(self.ui.jp2RootDirInput.text())

        return admin, adminpass, hvuser, hvpass, jp2dir, mysql

    def initEvents(self):
        print "Initializing..."
        QtCore.QObject.connect(self.ui.jp2BrowseBtn, QtCore.SIGNAL("clicked()"), self.openBrowseDialog)

    def openBrowseDialog(self):
        fd = QtGui.QFileDialog(self)
        directory = fd.getExistingDirectory()
        self.ui.jp2RootDirInput.setText(directory)

def loadGUIInstaller(args):
    ''' Load graphical installer '''
    app = QtGui.QApplication(sys.argv)
    win = HelioviewerInstallWizard()
    win.show()
    sys.exit(app.exec_())
