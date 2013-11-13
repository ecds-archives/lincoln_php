from django import forms

class DocSearchForm(forms.Form):
    "Search documentations by keyword/title/author/date/place"
    keyword = forms.CharField(required=False)
    title = forms.CharField(required=False)
    author = forms.CharField(required=False)
    sermon_Date = forms.CharField(required=False)
    Place_of_Publication = forms.CharField(required=False)

    def clean(self):
        """Custom form validation."""
        cleaned_data = self.cleaned_data

        keyword = cleaned_data.get('keyword')
        title = cleaned_data.get('title')
        author = cleaned_data.get('author')
        sermon_Date = cleaned_data.get('sermon_Date')
        Place_of_Publication = cleaned_data.get('Place_of_Publication')
 
       # raise forms.ValidationError("Date invalid")
       
        #Validate at least one term has been entered
        #if not title and not author and not keyword and not date:
        if not author and not title and not keyword and not sermon_Date and not Place_of_Publication:
            del cleaned_data['author']
            del cleaned_data['title']
            del cleaned_data['keyword']
            del cleaned_data['sermon_Date']
            del cleaned_data['Place_of_Publication']

            raise forms.ValidationError("Please enter search terms.")
          
        return cleaned_data
