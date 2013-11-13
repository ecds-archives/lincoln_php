# Django local settings for lincoln project

DEBUG = True
TEMPLATE_DEBUG = DEBUG
DEV_ENV = True

#Exist DB Settings
EXISTDB_SERVER_PROTOCOL = "http://"
# hostname, port, & path to exist xmlrpc - e.g., "localhost:8080/exist/xmlrpc"
EXISTDB_SERVER_HOST     = "kamina.library.emory.edu:8080/exist/"
EXISTDB_SERVER_USER     = "ahickco"
EXISTDB_SERVER_PASSWORD      = "uioJKL89"
#EXISTDB_SERVER_URL      = EXISTDB_SERVER_PROTOCOL + EXISTDB_SERVER_HOST
EXISTDB_SERVER_URL  = EXISTDB_SERVER_PROTOCOL + EXISTDB_SERVER_HOST
# collection should begin with / -  e.g., /edc
EXISTDB_ROOT_COLLECTION = "/lincoln/"
EXISTDB_TEST_COLLECTION = "/test/lincoln-AH"
# NOTE: EXISTDB_INDEX_CONFIGFILE is configured in settings.py (for fa; is it for gw?)

# from fa:
# a bug in python xmlrpclib loses the timezone; override it here
# most likely, you want either tz.tzlocal() or tz.tzutc()
from dateutil import tz
EXISTDB_SERVER_TIMEZONE = tz.tzlocal()
