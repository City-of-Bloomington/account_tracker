from rest_framework import viewsets
from django_filters import rest_framework as filters
from .models import AccountRequest
from .serializers import AccountRequestSerializer
from rest_framework.response import Response
from rest_framework.decorators import action
from service_request.models import ServiceRequest
from service.models import Service
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

        ad_displayName = ar.first_name + ' ' + ar.last_name
        ad_mail        = ar.first_name.lower() + '.' + ar.last_name.lower() + '@bloomington.in.gov'

        ad_payload = {
            "sAMAccountName": "string",
            "userPrincipalName": "string",
            "distinguishedName": "string",
            "givenName": "string",
            "displayName": ad_displayName,
            "sn": "string",
            "countryCode": "USA",
            "mail": ad_mail,
            "description": "string",
            "telephoneNumber": ar.employee_phone,
            "pager": "string",
            "facsimileTelephoneNumber": "string",
            "info": "string",
            "physicalDeliveryOfficeName": "string",
            "title": ar.job,
            "department": ar.department,
            "uid": "string",
            "employeeID": "string",
            "employeeNumber": "string"
        }

        r = requests.post('http://10.20.20.218:5000/index.html', json=ad_payload)
        r.raise_for_status()
        r.json()

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
