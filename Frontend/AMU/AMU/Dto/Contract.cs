using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AMU.Dto
{
    public class Contract
    {
        public int ID { get; set; }
        public int LocationID { get; set; }
        public int UserID { get; set; }
        public int OfferState { get; set; }
        public string OfferDate { get; set; }
        public int BandID { get; set; }
        public double Price { get; set; }
        public DateTime RecordDate { get; set; }
        public string VeranstaltungName { get; set; }
        public override string ToString()
        {
            return VeranstaltungName;
        }
    }
}
