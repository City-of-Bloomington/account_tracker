Account Tracker
========================
Account Tracker is the system we will use to manage access to all of the various systems city employees need access to for their jobs.  Hiring Supervisors will log into Account Tracker in order to request ITS accounts and resources for their staff.

The initial minimal viable product will consist of an "Activate" form that automates account creation in Active Directory.  Once we have this supported, we plan on putting Account Tracker into production.  This minimal feature set will save ITS staff a large amount of time, as well as provide an easier experience for supervisors requesting accounts for their new staff.

Onboarding New Hires
--------------------
We are starting with the "New Hire" form.  Account Tracker will provide a "New Hire" form that gathers all the information needed to create all the accounts needed for an employee.

Account Tracker will have "Profiles" that have a predefined set of accounts and resources usually needed for all the types of jobs in all the city departments.  A supervisor should not need to make changes to the resources requested on the "New Hire" form, but could do so, as needed.

Request Review Process
----------------------
Account requests must be reviewed and approved by Administrators.

Account Tracker will not support approvals for each individual resource being requested.  Rather, the Administrator will review and edit the entire request, including all the resources being requested.  Administrators will be able to edit the requests as needed before approving the request.

The accounts and resources are not created until the request is approved.  Once the request is approved, Account Tracker will create the accounts in the automated systems, and send email notifications to the maintainers of the resources that must be handled manually.

Software Resources
------------------
Account Tracker will have support for software that needs to be installed on an employee's workstation.  These software resources are also included in the "Profiles" we set up for departments and jobs.  The profiles should be set up so that only free (no-cost) software is included in the default profiles.  Premium software should require further review by Administrators before approving the request.

Security
--------
Account Tracker requires user authentication for any and all activity.  There will be no publicly visible information available.

In addition to user authentication, all automated communication between Account Tracker and the resources being created will be authenticated and encrypted.  Account Tracker will not rely on any URLs that do not support authenticated, encrypted requests.

Automation and the future
--------------------------
Although we're starting with only the "Activate" form, in the future Account Tracker will have support for "Termination" requests and "Account Change" requests.

Initially, we will only be automating the entry of the employee information into Active Directory.  Until the rest of the resources are automated, Account Tracker will send an email to the maintainer of each resource.  These notifications will prompt the maintainers to manually add the new employee into the requested system. (Timetrack, uReport, Showers, etc.)

Account Tracker will be built with a modular way of defining the resources that can be requested.  The goal is to be able to implement automation modules for individual resources in the future, as time allows.
