from rest_framework import viewsets
from django_filters import rest_framework as filters
from .models import AccountRequest
from .serializers import AccountRequestSerializer
from rest_framework.response import Response
from rest_framework.decorators import action
from service_request.models import ServiceRequest
from service.models import Service
import requests
import json


class ProfileFilter(filters.FilterSet):
    request_status = filters.CharFilter(field_name="request_status")
    id = filters.NumberFilter(field_name="id")

    class Meta:
        model = AccountRequest
        fields = ['request_status', 'id']


class AccountRequestViewSet(viewsets.ModelViewSet):
    queryset = AccountRequest.objects.all()
    serializer_class = AccountRequestSerializer
    filter_backends = (filters.DjangoFilterBackend,)
    filterset_class = ProfileFilter

    @action(detail=True)
    def pending(self, request, *args, **kwargs):
        ar = self.get_object()

        ad_distinguishedName = ('CN=' + ar.first_name + ' ' + ar.last_name +
                                ',OU=' + ar.department +
                                ',OU=' + 'City Hall' +
                                ',OU=' + 'Departments' +
                                ',DC=' + 'seth' +
                                ',DC=' + 'test')

        ad_displayName      = ar.first_name + ' ' + ar.last_name

        ad_sAMAccountName   = (ar.first_name.lower() + '.' +
                               ar.last_name.lower())

        ad_mail             = (ar.first_name.lower() + '.' +
                               ar.last_name.lower() +
                               '@bloomington.in.gov')

        ad_payload = {
            "sAMAccountName":       ad_sAMAccountName,
            "userPrincipalName":    ad_mail,
            "distinguishedName":    ad_distinguishedName,
            "givenName":            ar.first_name,
            "displayName":          ad_displayName,
            "sn":                   ar.last_name,
            "countryCode":          0,
            "mail":                 ad_mail,
            #"description":          null,
            "telephoneNumber":      ar.employee_phone,
            #"pager":                null,
            #"facsimileTelephoneNumber": null,
            #"info":                 null,
            #"physicalDeliveryOfficeName": null,
            "title":                ar.job,
            "department":           ar.department,
            #"uid":                  null,
            #"employeeID":           null,
            #"employeeNumber":       null
        }

        r = requests.post('http://10.20.20.218:5000/api/NovellDirectory', json=ad_payload)
        r.raise_for_status()
        print(r)
        print(r.text)
        print(r.json())

        service_list = json.loads(ar.requested_services)
        for service_id in service_list:
            # get the corresponding Service
            # to facilitate creating ServiceRequests
            service = Service.objects.get(id=service_id)
            existing = ServiceRequest.objects.filter(service=service, account_request=ar)
            # print(existing)
            # print(existing.count())
            if not existing.count():
                sr = ServiceRequest()
                sr.account_request = ar
                sr.service = service
                sr.type_of_change = 'grant'
                sr.request_status = 'new'
                sr.save()
                # print("Created:", sr)

        # TODO:
        # This is where any automated tasks could be handled
        # (should still be tracked in a service request + actions)
        return Response(service_list)
