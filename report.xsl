<!-- 
 Author: Nilanshu Basnet
 StudentID: 104346575
 Main Function: The XSLT stylesheet transforms XML data into an HTML report that displays a summary of auction results, 
 including tables of sold items and failed items with relevant details, as well as the total revenue generated from sold 
 items. -->
<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="/">
        <html>
        <body>
            <h3>Sold Items</h3>
            <table border="1">
                <tr bgcolor="#9acd32">
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Starting Price</th>
                    <th>Final Bid Price</th>
                    <th>Customer ID</th>
                    <th>Status</th>
                </tr>
                <xsl:for-each select="report/soldItems/item">
                    <tr>
                        <td><xsl:value-of select="itemID"/></td>
                        <td><xsl:value-of select="itemName"/></td>
                        <td><xsl:value-of select="category"/></td>
                        <td><xsl:value-of select="startingPrice"/></td>
                        <td><xsl:value-of select="bidPrice"/></td>
                        <td><xsl:value-of select="bidderID"/></td>
                        <td><xsl:value-of select="status"/></td>
                    </tr>
                </xsl:for-each>
            </table>

            <h3>Failed Items</h3>
            <table border="1">
                <tr bgcolor="#ff6347">
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Starting Price</th>
                    <th>Reserve Price</th>
                    <th>Status</th>
                </tr>
                <xsl:for-each select="report/failedItems/item">
                    <tr>
                        <td><xsl:value-of select="itemID"/></td>
                        <td><xsl:value-of select="itemName"/></td>
                        <td><xsl:value-of select="category"/></td>
                        <td><xsl:value-of select="startingPrice"/></td>
                        <td><xsl:value-of select="reservePrice"/></td>
                        <td><xsl:value-of select="status"/></td>
                    </tr>
                </xsl:for-each>
            </table>

            <h3>Total Revenue: $<xsl:value-of select="report/totalRevenue"/></h3>
        </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
