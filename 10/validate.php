<?php

// Create an XML file with the given structure
$xml = <<<XML
<student_details>
    <student registration_number="1234">
        <first_name>John</first_name>
        <last_name>Doe</last_name>
        <department>Computer Science</department>
        <address>123 Main Street</address>
        <phone_number>123-4567</phone_number>
    </student>
</student_details>
XML;

// Create a SimpleXMLElement object from the XML file
$student_details = new SimpleXMLElement($xml);

// Function to validate student details using Schema
function validateStudentDetails($student_details) {
    // Define the XSD schema for validation
    $xsd = <<<XSD
<?xml version="1.0"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">
    <xs:element name="student_details">
        <xs:complexType>
            <xs:sequence>
                <xs:element name="student" maxOccurs="unbounded">
                    <xs:complexType>
                        <xs:sequence>
                            <xs:element name="first_name" type="xs:string"/>
                            <xs:element name="last_name" type="xs:string"/>
                            <xs:element name="department" type="xs:string"/>
                            <xs:element name="address" type="xs:string"/>
                            <xs:element name="phone_number" type="xs:string"/>
                        </xs:sequence>
                        <xs:attribute name="registration_number" type="xs:integer" use="required"/>
                    </xs:complexType>
                </xs:element>
            </xs:sequence>
        </xs:complexType>
    </xs:element>
</xs:schema>
XSD;

    // Create a DOMDocument object
    $dom_document = new DOMDocument();
    $dom_document->loadXML($student_details->asXML());

    // Create a DOMDocument object from the XSD schema
    $dom_xsd = new DOMDocument();
    $dom_xsd->loadXML($xsd);

    // Create a DOMXSDSchema object
    $dom_xs = new DOMDocument();
    $dom_xs->loadXML($xsd);

    // Validate the XML document against the schema
    return $dom_document->schemaValidateSource($dom_xs->saveXML());
}

// Call the function to validate the student details
if (validateStudentDetails($student_details)) {
    echo "The student details are valid.";
} else {
    echo "The student details are not valid.";
}

?>
