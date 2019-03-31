using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AMU.Dto
{
    public class Bill
    {
        public int OfferBandID { get; set; }
        public int LocationID { get; set; }
        public int UserID { get; set; }
        public int OfferState { get; set; }
        public string OfferDate { get; set; }
        public int BandID { get; set; }
        public double Price { get; set; }
        public string InvoiceNumber { get; set; }
        public DateTime InvoiceDate { get; set; }
        public DateTime Record_Date { get; set; }
        public override string ToString()
        {
            return InvoiceNumber;
        }
    }
}
