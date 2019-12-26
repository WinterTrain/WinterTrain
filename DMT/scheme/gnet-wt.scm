;; gnetlist development playground
 
(use-modules (ice-9 readline))

(activate-readline)

(define format-line
     (lambda (name value)
         (display name)
         (display value)
         (newline)
     )
)

(define next-element
  (lambda (name pin)
    (car (car (cdr (gnetlist:get-nets name pin))))
  )
)

(define wt:list-elements
  (lambda ()
    (for-each (lambda (name)
      (display "\"") (display name) (display "\" => [ // ") (display (gnetlist:get-package-attribute name "device")) (newline)
      (let ((element (gnetlist:get-package-attribute name "element")))
        (display "  \"element\" => \"") (display element) (display "\",\n")
        (cond 
        ((or (string=? element "PF") (string=? element "PT"))
          (display "  \"T\" => [\"name\" => \"") (display (next-element name "0"))
          (display "\", \"dist\" => ") (display (gnetlist:get-package-attribute name "dTip")) (display "],\n")
          (display "  \"R\" => [\"name\" => \"") (display (next-element name "1"))
          (display "\", \"dist\" => ") (display (gnetlist:get-package-attribute name "dRight")) (display "],\n")
          (display "  \"L\" => [\"name\" => \"") (display (next-element name "2"))
          (display "\", \"dist\" => ") (display (gnetlist:get-package-attribute name "dLeft")) (display "],\n")
          (display "  \"supervisionState\" => \"") (display (gnetlist:get-package-attribute name "supervisionState")) (display "\",\n")
          (display "  \"EC\" => [\n")
          (display "    \"addr\" => ") (display (gnetlist:get-package-attribute name "EC_addr")) (display ",\n")
          (display "    \"type\" => ") (display (gnetlist:get-package-attribute name "EC_type")) (display ",\n")
          (display "    \"majorDevice\" => ") (display (gnetlist:get-package-attribute name "EC_major")) (display ",\n")
          (display "    \"minorDevice\" => ") (display (gnetlist:get-package-attribute name "EC_minor")) (display ",\n")
          (display "  ],\n")
        )
        ((or (string=? element "PHTU") (string=? element "PHTD"))
          (display "  \"U\" => [\"name\" => \"") (display (next-element name "1"))
          (display "\", \"dist\" => ") (display (gnetlist:get-package-attribute name "dUp")) (display "],\n")
          (display "  \"D\" => [\"name\" => \"") (display (next-element name "0"))
          (display "\", \"dist\" => ") (display (gnetlist:get-package-attribute name "dDown")) (display "],\n")
          (display "  \"holdPoint\" => \"") (display (gnetlist:get-package-attribute name "HoldPoint")) (display "\",\n")
        )
        ((string=? element "BL")
          (display "  \"U\" => [\"name\" => \"") (display (next-element name "1"))
          (display "\", \"dist\" => ") (display (gnetlist:get-package-attribute name "dUp")) (display "],\n")
          (display "  \"D\" => [\"name\" => \"") (display (next-element name "0"))
          (display "\", \"dist\" => ") (display (gnetlist:get-package-attribute name "dDown")) (display "],\n")
          (display "  \"ID\" => \"") (display (gnetlist:get-package-attribute name "ID")) (display "\",\n")
        )
        ((or (string=? element "SU") (string=? element "SD"))
          (display "  \"U\" => [\"name\" => \"") (display (next-element name "1"))
          (display "\", \"dist\" => ") (display (gnetlist:get-package-attribute name "dUp")) (display "],\n")
          (display "  \"D\" => [\"name\" => \"") (display (next-element name "0"))
          (display "\", \"dist\" => ") (display (gnetlist:get-package-attribute name "dDown")) (display "],\n")
          (display "  \"type\" => \"") (display (gnetlist:get-package-attribute name "type")) (display "\",\n")
          (display "  \"EC\" => [\n")
          (display "    \"addr\" => ") (display (gnetlist:get-package-attribute name "EC_addr")) (display ",\n")
          (display "    \"type\" => ") (display (gnetlist:get-package-attribute name "EC_type")) (display ",\n")
          (display "    \"majorDevice\" => ") (display (gnetlist:get-package-attribute name "EC_major")) (display ",\n")
          (display "  ],\n")
        )
        ((string=? element "BSB")
          (display "  \"U\" => [\"name\" => \"") (display (next-element name "0"))
          (display "\", \"dist\" => ") (display (gnetlist:get-package-attribute name "dUp")) (display "],\n")
        )
        ((string=? element "BSE")
          (display "  \"D\" => [\"name\" => \"") (display (next-element name "0"))
          (display "\", \"dist\" => ") (display (gnetlist:get-package-attribute name "dDown")) (display "],\n")
        )
        )
        (display "],\n")
        )
      ) packages)
  )
)


(define wt
  (lambda (filename)
    (set-current-output-port (open-output-file filename))       
	  (display "<?php\n$PT1 = [\n")
	  (wt:list-elements)
    (display "]\n?>")
  )
)



