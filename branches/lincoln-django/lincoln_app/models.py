from django.utils.safestring import mark_safe
from django.db import models
from eulexistdb.manager import Manager
from eulexistdb.models import XmlModel
from eulxml import xmlmap
from eulxml.xmlmap.core import XmlObject
from eulxml.xmlmap.dc import DublinCore
from eulxml.xmlmap.fields import StringField, NodeField, StringListField, NodeListField, IntegerField
from eulxml.xmlmap.teimap import Tei, TeiDiv, TEI_NAMESPACE

class Bibliography(XmlObject):
    ROOT_NAMESPACES = {'tei' : TEI_NAMESPACE}
    # TODO: handle repeating elements
    title = StringField('tei:title')
    author = StringField('tei:author')
    editor = StringField('tei:editor')
    publisher = StringField('tei:publisher')
    pubplace = StringField('tei:pubPlace')
    date = StringField('tei:date')

    def formatted_citation(self):
        """Generate an HTML formatted citation."""
        cit = {
            "author": '',
            "editor": '',
            "title": self.title,
            "pubplace": self.pubplace,
            "publisher":  self.publisher,
            "date": self.date
        }
        if self.author:
            cit['author'] = '%s. ' % self.author
        if self.editor:
            cit['editor'] = '%s, ed. ' % self.editor

        return mark_safe('%(author)s%(editor)s<i>%(title)s</i>. %(pubplace)s: %(publisher)s, %(date)s.' \
                % cit)


class SourceDescription(XmlObject):
    'XmlObject for TEI Source Description (sourceDesc element).'
    ROOT_NAMESPACES = {'tei' : TEI_NAMESPACE}
    bibl = NodeField('tei:bibl', Bibliography)
    ':class:`Bibliography` - `@bibl`'

    def citation(self):
        'Shortcut for :meth:`Bibligraphy.formatted_citation` to render source bibl'
        return self.bibl.formatted_citation()

class DocTitle(XmlModel, Tei):
    ROOT_NAMESPACES = {'tei' : TEI_NAMESPACE}
    objects = Manager('/tei:TEI')
    text = StringField('tei:text')
    date =  StringListField('tei:text/tei:body/tei:div1/tei:head/tei:bibl/tei:date')
    author =  StringField('tei:text/tei:body/tei:div1/tei:head/tei:bibl/tei:author')
    pubplace = StringListField('tei:text/tei:body/tei:div1/tei:head/tei:bibl/tei:pubPlace')
    id = StringListField('tei:text/tei:body/tei:div1/@xml:id')
    title = StringListField('tei:text/tei:body/tei:div1/tei:head/tei:bibl/tei:title')
    matchcount = IntegerField("count(.//exist:match)")

    author_rev = StringField('tei:teiHeader/tei:fileDesc/tei:titleStmt/tei:author')
    publisher = StringField('tei:teiHeader/tei:fileDesc/tei:publicationStmt/tei:publisher')
    rights = StringField('tei:teiHeader/tei:fileDesc/tei:publicationStmt/tei:availability')
    publication_date = StringField('tei:teiHeader/tei:fileDesc/tei:publicationStmt/tei:date')
    relation = StringField('tei:teiHeader/tei:fileDesc/tei:seriesStmt/tei:title')
    source = StringField('tei:teiHeader/tei:fileDesc/tei:sourceDesc/tei:bibl')
    project_desc = StringField('tei:teiHeader/tei:encodingDesc/tei:projectDesc')

    site_url = 'http://http://beck.library.emory.edu/lincoln'
    geo_coverage = StringField('tei:teiHeader/tei:profileDesc/tei:creation/tei:rs[@type="geography"]')
    lcsh_subjects = StringListField('tei:teiHeader//tei:keywords[@scheme="#lcsh"]/tei:list/tei:item')
    identifier_ark = StringField('tei:text/tei:body/tei:div1/tei:head/tei:bibl/tei:idno[@type="ark"]')
    
    
    def dc_fields(self):
        dc = DublinCore()
        dc.title = self.title
        dc.creator = self.author
        dc.identifier = self.identifier_ark
        dc.publisher = self.header.publisher
        dc.rights = self.header.availability
        dc.date = self.header.publication_date
        dc.source = self.header.source_description
        dc.description = self.project_desc


        if self.geo_coverage:
            dc.coverage_list.append(self.geo_coverage)
        if self.creation_date:
            dc.coverage_list.append(self.creation_date)

        if self.header.series_statement:
            dc.relation_list.append(self.header.series_statement)
        # FIXME: should we also include url? site name & url are currently
        # hard-coded when setting dc:relation in postcard ingest

        return dc

class Doc(XmlModel, TeiDiv):
    ROOT_NAMESPACES = {'tei' : TEI_NAMESPACE}
    sermon = NodeField("tei:div1", "self")

    objects = Manager("//tei:div1")

    doct = xmlmap.NodeField('ancestor::tei:TEI', DocTitle)
    author = StringField('//tei:div1/tei:head/tei:bibl/tei:author')
    title = StringField('//tei:div1/tei:head/tei:bibl/tei:title')
    identifier = StringField('//tei:div1/tei:head/tei:bibl/tei:idno')
    image_id = StringField('//tei:div1//tei:pb/@facs')
    nextfigure = StringField('following-sibling::tei:div1//tei:pb[1]/@facs')
    prevfigure = StringField('preceding-sibling::tei:div1//tei:pb[1]/@facs')
    
    
class DocSearch(Doc):
   objects = Manager("//tei:div1")



