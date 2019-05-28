from django.db import models
from django.contrib.auth.models import User


class AccountRequest(models.Model):
    """
    A request is what triggers the required actions to create an account

    associations with software / services / accounts
    will be tracked elsewhere with ForeignKeys
    """
    full_image = models.FileField(upload_to='%Y/%m/%d', null=True)
    cropped_image = models.FileField(upload_to='%Y/%m/%d', null=True)

    requester = models.ForeignKey(User, on_delete=models.SET_NULL,
                                  blank=True, null=True)

    first_name = models.CharField(max_length=50)
    middle_name = models.CharField(max_length=50, blank=True, null=True)
    last_name = models.CharField(max_length=50)
    suffix = models.CharField(max_length=10, blank=True, null=True)
    nickname = models.CharField(max_length=50, blank=True, null=True)

    # strip out dashes and periods
    employee_phone = models.CharField(max_length=12)

    # Ideally this would reference a User object
    supervisor = models.CharField(max_length=50)

    # Leaving this as not required till we can get a way to access ...
    supervisor_phone = models.CharField(max_length=12, blank=True, null=True)

    department = models.CharField(max_length=50)
    group = models.CharField(max_length=50)

    # can these be determined from the group?
    division = models.CharField(max_length=50, blank=True, null=True)
    facility = models.CharField(max_length=50, blank=True, null=True)

    # Some employees are only allowed to keep time with a punch clock...
    clock_entry_only = models.BooleanField(default=False)

    job = models.CharField(max_length=50)
    # e.g. temp, full-time
    employee_status = models.CharField(max_length=50)
    start_date = models.DateField()

    # optional, only necessary if known (e.g. seasonal)
    end_date = models.DateField(blank=True, null=True)

    # used for storing any other fields
    # that are only applicable to a subset of employees
    # store as JSON object
    dynamic_options = models.TextField(blank=True, null=True)

    # TODO:
    # this should be migrated to use an action instead
    # in case something is denied, could add a note about why
    comment = models.TextField(default='', blank=True, null=True)

    # Temp. placeholder for services associated w/ an account request
    requested_services = models.TextField(blank=True, null=True)
    # Is this worth migrating to a many to many relationship?

    # is it new? approved? completed? denied?
    request_status = models.CharField(max_length=50)

    # who approved the account request
    # should this simply be an action?
    # subsequent actions should be tracked in the sub-service_requests
    # approver = models.ForeignKey(User, on_delete=models.SET_NULL,
    #                              blank=True, null=True)

    requested = models.DateTimeField(auto_now_add=True)
    # any associated logged actions could trigger an update to updated
    updated = models.DateTimeField(auto_now=True)
    # when the account was created -- job's finished
    created = models.DateTimeField(blank=True, null=True)
